<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Profile Controller - User Profile & Settings Management
 * =========================================================================
 */

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\PgpKey;
use App\Models\PrivateMirrorRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Response as ResponseFacade;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Encoders\GifEncoder;
use Intervention\Image\Exceptions\NotReadableException;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Exception;

class ProfileController extends Controller
{
    /**
     * PGP confirmation expiry in minutes
     */
    private const CONFIRMATION_EXPIRY_MINUTES = 16;

    /**
     * Allowed MIME types for profile pictures
     *
     * @var array<string>
     */
    private array $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp'
    ];

    /**
     * Initialize middleware for authentication
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display user profile page
     *
     * @return View
     */
    public function index(): View
    {
        try {
            Log::debug('Loading user profile', ['user_id' => Auth::id()]);

            $user = Auth::user();
            $profile = $user->profile ?? $user->profile()->create();

            // Get PGP key
            $pgpKey = $user->pgpKey;

            // Determine user role
            $userRole = $this->determineUserRole($user);

            // Get reference information
            $referenceId = $user->reference_id;
            $referrer = $user->referrer;
            $usedReferenceCode = $referrer !== null;
            $referrerUsername = $usedReferenceCode ? $referrer->username : null;

            // Get referrals
            $referrals = $user->referrals;

            // Get private shops
            $privateShops = DB::table('private_shops')
                ->where('user_id', $user->id)
                ->join('users', 'private_shops.vendor_id', '=', 'users.id')
                ->select('private_shops.id', 'private_shops.vendor_reference_id', 'users.username as vendor_username')
                ->get();

            // Get mirror information
            $assignedMirrorRequest = $user->getAssignedMirrorRequest();
            $hasPendingMirrorRequest = $user->hasPendingMirrorRequest();
            $assignedMirrorUrl = $assignedMirrorRequest?->assigned_mirror;

            Log::debug('Profile loaded', [
                'user_id' => Auth::id(),
                'role' => $userRole,
            ]);

            return view('profile.index', compact(
                'user',
                'profile',
                'pgpKey',
                'userRole',
                'referenceId',
                'usedReferenceCode',
                'referrerUsername',
                'referrals',
                'privateShops',
                'assignedMirrorUrl',
                'hasPendingMirrorRequest'
            ));

        } catch (\Exception $e) {
            Log::error('Error loading profile', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return view('profile.index', [
                'user' => Auth::user(),
                'profile' => Auth::user()->profile,
                'pgpKey' => null,
                'userRole' => 'Buyer',
                'referenceId' => Auth::user()->reference_id ?? null,
                'usedReferenceCode' => false,
                'referrerUsername' => null,
                'referrals' => collect(),
                'privateShops' => collect(),
                'assignedMirrorUrl' => null,
                'hasPendingMirrorRequest' => false,
            ])->with('error', 'An error occurred while loading your profile.');
        }
    }

    /**
     * Determine user's role
     *
     * @param mixed $user
     * @return string
     */
    private function determineUserRole($user): string
    {
        if ($user->hasRole('admin') && $user->hasRole('vendor')) {
            return 'Admin & Vendor';
        } elseif ($user->hasRole('admin')) {
            return 'Administrator';
        } elseif ($user->hasRole('vendor')) {
            return 'Vendor';
        }

        return 'Buyer';
    }

    /**
     * Update user profile
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        try {
            Log::debug('Updating profile', ['user_id' => Auth::id()]);

            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'description' => [
                    'required',
                    'string',
                    'min:4',
                    'max:800',
                    'regex:/^[\p{L}\p{N}\s\p{P}]+$/u'
                ],
                'profile_picture' => [
                    'nullable',
                    'file',
                    'max:800',
                ],
            ], [
                'description.regex' => 'Description can only contain letters, numbers, spaces, and punctuation marks.',
                'profile_picture.max' => 'Profile picture must not be larger than 800KB.',
            ]);

            if ($validator->fails()) {
                Log::warning('Profile update validation failed', [
                    'user_id' => Auth::id(),
                    'errors' => $validator->errors()->toArray(),
                ]);

                return redirect()->route('profile')
                    ->with('error', $validator->errors()->first())
                    ->withInput();
            }

            $user = Auth::user();
            $profile = $user->profile ?? $user->profile()->create();

            // Update description (encrypted)
            $profile->description = Crypt::encryptString($request->input('description'));

            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                try {
                    $this->handleProfilePictureUpload($request->file('profile_picture'), $profile);
                } catch (\Exception $e) {
                    Log::error('Profile picture upload failed', [
                        'user_id' => Auth::id(),
                        'message' => $e->getMessage(),
                    ]);

                    return redirect()->route('profile')
                        ->with('error', $e->getMessage())
                        ->withInput();
                }
            }

            $profile->save();

            Log::info('Profile updated successfully', ['user_id' => Auth::id()]);

            return redirect()->route('profile')
                ->with('success', 'Profile successfully updated.');

        } catch (\Exception $e) {
            Log::error('Profile update error', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('profile')
                ->with('error', 'An error occurred while updating your profile. Please try again.');
        }
    }

    /**
     * Delete user's profile picture
     *
     * @return RedirectResponse
     */
    public function deleteProfilePicture(): RedirectResponse
    {
        try {
            Log::debug('Deleting profile picture', ['user_id' => Auth::id()]);

            $user = Auth::user();
            $profile = $user->profile;

            if ($profile && $profile->profile_picture) {
                if (!Storage::disk('private')->delete('profile_pictures/' . $profile->profile_picture)) {
                    throw new \Exception('Failed to delete profile picture from storage');
                }

                $profile->profile_picture = null;
                $profile->save();

                Log::info('Profile picture deleted', ['user_id' => Auth::id()]);
            }

            return redirect()->route('profile')
                ->with('success', 'Profile picture successfully deleted.');

        } catch (\Exception $e) {
            Log::error('Profile picture deletion error', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('profile')
                ->with('error', 'An error occurred while deleting your profile picture. Please try again.');
        }
    }

    /**
     * Handle profile picture upload
     *
     * @param object $file
     * @param Profile $profile
     * @return void
     * @throws Exception
     */
    private function handleProfilePictureUpload($file, Profile $profile): void
    {
        try {
            Log::debug('Processing profile picture upload', ['user_id' => Auth::id()]);

            // Verify MIME type
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($file->getPathname());

            if (!in_array($mimeType, $this->allowedMimeTypes)) {
                throw new \Exception('Invalid file type. Allowed types are JPEG, PNG, GIF, and WebP.');
            }

            // Delete old picture if exists
            if ($profile->profile_picture) {
                if (!Storage::disk('private')->delete('profile_pictures/' . $profile->profile_picture)) {
                    throw new \Exception('Failed to delete old profile picture from storage');
                }
            }

            $extension = $this->getExtensionFromMimeType($mimeType);
            $filename = time() . '_' . Str::uuid() . '.' . $extension;

            // Create ImageManager instance
            $manager = new ImageManager(new GdDriver());

            // Resize image
            $image = $manager->read($file)
                ->resize(160, 160, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

            // Encode image
            $encodedImage = $this->encodeImage($image, $mimeType);

            // Save to storage
            if (!Storage::disk('private')->put('profile_pictures/' . $filename, $encodedImage)) {
                throw new \Exception('Failed to save profile picture to storage');
            }

            $profile->profile_picture = $filename;

            Log::info('Profile picture uploaded', [
                'user_id' => Auth::id(),
                'filename' => $filename,
            ]);

        } catch (NotReadableException $e) {
            Log::error('Image processing error', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);

            throw new \Exception('Could not process the uploaded image. Please try a different image.');

        } catch (\Exception $e) {
            Log::error('Profile picture upload error', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Get file extension from MIME type
     *
     * @param string $mimeType
     * @return string
     */
    private function getExtensionFromMimeType(string $mimeType): string
    {
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp'
        ];

        return $extensions[$mimeType] ?? 'jpg';
    }

    /**
     * Encode image based on MIME type
     *
     * @param mixed $image
     * @param string $mimeType
     * @return mixed
     */
    private function encodeImage($image, string $mimeType)
    {
        return match ($mimeType) {
            'image/png' => $image->encode(new PngEncoder()),
            'image/webp' => $image->encode(new WebpEncoder()),
            'image/gif' => $image->encode(new GifEncoder()),
            default => $image->encode(new JpegEncoder(80)),
        };
    }

    /**
     * Get and serve profile picture
     *
     * @param string $filename
     * @return Response
     */
    public function getProfilePicture(string $filename): Response
    {
        try {
            // Verify authentication
            if (!Auth::check()) {
                Log::warning('Unauthorized profile picture access', ['filename' => $filename]);
                abort(403, 'Unauthorized action.');
            }

            Log::debug('Retrieving profile picture', [
                'user_id' => Auth::id(),
                'filename' => $filename,
            ]);

            // Verify path safety
            if (str_contains($filename, '..') || str_contains($filename, '/')) {
                throw new \Exception('Invalid filename');
            }

            $path = 'profile_pictures/' . $filename;

            // Check file exists
            if (!Storage::disk('private')->exists($path)) {
                throw new \Exception('Profile picture not found');
            }

            // Get file content
            $file = Storage::disk('private')->get($path);

            // Verify MIME type
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($file);

            if (!in_array($mimeType, $this->allowedMimeTypes)) {
                throw new \Exception('Invalid file type');
            }

            // Create response
            $response = ResponseFacade::make($file, 200);
            $response->header('Content-Type', $mimeType);

            return $response;

        } catch (\Exception $e) {
            Log::error('Profile picture retrieval error', [
                'user_id' => Auth::id(),
                'filename' => $filename ?? 'unknown',
                'message' => $e->getMessage(),
            ]);

            abort(404, 'Profile picture not found');
        }
    }

    /**
     * Show PGP key confirmation form
     *
     * @return View|RedirectResponse
     */
    public function showPgpConfirmationForm(): View|RedirectResponse
    {
        try {
            Log::debug('Loading PGP confirmation form', ['user_id' => Auth::id()]);

            $user = Auth::user();
            $pgpKey = $user->pgpKey;

            // Verify key exists and is not verified
            if (!$pgpKey || !$pgpKey->public_key) {
                Log::warning('PGP key not found', ['user_id' => Auth::id()]);
                return redirect()->route('settings')
                    ->with('error', 'You must add a PGP key first.');
            }

            if ($pgpKey->verified) {
                Log::info('PGP key already verified', ['user_id' => Auth::id()]);
                return redirect()->route('settings')
                    ->with('info', 'Your PGP key is already verified.');
            }

            // Generate message
            $message = 'EREBUS-' . mt_rand(1000000000, 9999999999) . '-SCRIPT';
            $encryptedMessage = '';

            // Create temporary directory
            $tempDir = sys_get_temp_dir() . '/gnupg_' . uniqid();
            mkdir($tempDir, 0700);

            try {
                // Set GNUPGHOME
                putenv('GNUPGHOME=' . $tempDir);

                // Create GnuPG instance
                $gpg = new \gnupg();
                $gpg->seterrormode(\gnupg::ERROR_EXCEPTION);

                // Import public key
                $importInfo = $gpg->import($pgpKey->public_key);

                if (!empty($importInfo['fingerprint'])) {
                    $gpg->addencryptkey($importInfo['fingerprint']);
                    $encryptedMessage = $gpg->encrypt($message);

                    Log::debug('PGP message encrypted', ['user_id' => Auth::id()]);
                } else {
                    throw new \Exception('Failed to import the public key. No fingerprint returned.');
                }

            } catch (\Exception $e) {
                Log::error('PGP encryption error', [
                    'user_id' => Auth::id(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return redirect()->route('settings')
                    ->with('error', 'An error occurred while processing your PGP key. Please try again or contact support.');

            } finally {
                $this->cleanupTempDir($tempDir);
            }

            // Store in session
            $expirationTime = Carbon::now()->addMinutes(self::CONFIRMATION_EXPIRY_MINUTES);
            session([
                'pgp_confirmation_message' => $message,
                'pgp_confirmation_expiry' => $expirationTime
            ]);

            return view('profile.confirm-pgp-key', compact('encryptedMessage', 'expirationTime'));

        } catch (\Exception $e) {
            Log::error('PGP confirmation form error', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('settings')
                ->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Confirm PGP key
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function confirmPgpKey(Request $request): RedirectResponse
    {
        try {
            Log::debug('Confirming PGP key', ['user_id' => Auth::id()]);

            $user = Auth::user();
            $pgpKey = $user->pgpKey;

            // Verify key exists and is unverified
            if (!$pgpKey || !$pgpKey->public_key) {
                Log::warning('PGP key not found for confirmation', ['user_id' => Auth::id()]);
                return redirect()->route('settings')
                    ->with('error', 'You must add a PGP key first.');
            }

            if ($pgpKey->verified) {
                Log::info('PGP key already verified', ['user_id' => Auth::id()]);
                return redirect()->route('settings')
                    ->with('info', 'Your PGP key is already verified.');
            }

            // Validate decrypted message
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'decrypted_message' => [
                    'required',
                    'string',
                    'regex:/^EREBUS-\d{10}-SCRIPT$/'
                ],
            ], [
                'decrypted_message.regex' => 'The decrypted message must be in the correct format (EREBUS-{number}-SCRIPT).',
            ]);

            if ($validator->fails()) {
                Log::warning('PGP message validation failed', [
                    'user_id' => Auth::id(),
                    'errors' => $validator->errors()->toArray(),
                ]);

                return back()
                    ->with('error', $validator->errors()->first())
                    ->withInput();
            }

            // Get original message and expiry
            $originalMessage = session('pgp_confirmation_message');
            $expirationTime = session('pgp_confirmation_expiry');
            $decryptedMessage = $request->input('decrypted_message');

            // Check expiry
            if (Carbon::now()->isAfter($expirationTime)) {
                Log::warning('PGP confirmation expired', ['user_id' => Auth::id()]);
                return redirect()->route('pgp.confirm')
                    ->with('error', 'Verification process has timed out. Please try again.');
            }

            // Verify message matches
            if ($decryptedMessage === $originalMessage) {
                try {
                    $pgpKey->verified = true;
                    $pgpKey->save();

                    Log::info('PGP key verified successfully', ['user_id' => Auth::id()]);

                    return redirect()->route('settings')
                        ->with('success', 'Your PGP key has been successfully verified!');

                } catch (QueryException $e) {
                    Log::error('PGP verification save error', [
                        'user_id' => Auth::id(),
                        'message' => $e->getMessage(),
                    ]);

                    return redirect()->route('settings')
                        ->with('error', 'An error occurred while verifying your PGP key. Please try again or contact support.');
                }
            } else {
                Log::warning('PGP message mismatch', ['user_id' => Auth::id()]);
                return back()
                    ->with('error', 'The decrypted message does not match. Please try again.')
                    ->withInput();
            }

        } catch (\Exception $e) {
            Log::error('PGP confirmation error', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('settings')
                ->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Request a private mirror
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function requestPrivateMirror(Request $request): RedirectResponse
    {
        try {
            Log::debug('Creating private mirror request', ['user_id' => Auth::id()]);

            $user = Auth::user();

            // Check pending request
            if ($user->hasPendingMirrorRequest()) {
                Log::info('User already has pending mirror request', ['user_id' => Auth::id()]);
                return redirect()->route('profile')
                    ->with('info', 'You already have a pending mirror request.');
            }

            // Check assigned mirror
            if ($user->getAssignedMirrorRequest()) {
                Log::info('User already has assigned mirror', ['user_id' => Auth::id()]);
                return redirect()->route('profile')
                    ->with('info', 'You already have a private mirror assigned.');
            }

            // Create request
            $mirrorRequest = $user->createPrivateMirrorRequest();

            if (!$mirrorRequest) {
                Log::warning('Unable to create mirror request', ['user_id' => Auth::id()]);
                return redirect()->route('profile')
                    ->with('error', 'Unable to create mirror request. You may already have a pending or assigned mirror.');
            }

            Log::info('Private mirror request created', [
                'user_id' => Auth::id(),
                'request_id' => $mirrorRequest->id,
            ]);

            return redirect()->route('profile')
                ->with('success', 'Mirror request submitted successfully. An admin will review your request shortly.');

        } catch (\Exception $e) {
            Log::error('Mirror request creation error', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('profile')
                ->with('error', 'An error occurred while submitting your request. Please try again.');
        }
    }

    /**
     * Clean up temporary directory
     *
     * @param string $dir
     * @return void
     */
    private function cleanupTempDir(string $dir): void
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);

            foreach ($objects as $object) {
                if ($object !== '.' && $object !== '..') {
                    $path = $dir . '/' . $object;

                    if (is_dir($path)) {
                        $this->cleanupTempDir($path);
                    } else {
                        unlink($path);
                    }
                }
            }

            rmdir($dir);
        }
    }
}
