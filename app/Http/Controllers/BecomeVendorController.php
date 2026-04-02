<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Become Vendor Controller - Vendor Application & Payment
 * =========================================================================
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use MoneroIntegrations\MoneroPhp\walletRPC;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use App\Models\VendorPayment;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Encoders\GifEncoder;
use Intervention\Image\Exceptions\NotReadableException;

class BecomeVendorController extends Controller
{
    /**
     * Monero wallet RPC instance
     *
     * @var walletRPC|null
     */
    protected walletRPC|null $walletRPC;

    /**
     * Initialize controller with Monero RPC connection
     */
    public function __construct()
    {
        Log::info('BecomeVendorController constructor called');

        $config = config('monero');
        $requiredAmount = (float)$config['vendor_payment_required_amount'];
        Log::info('Required amount: ' . $requiredAmount);

        // Only initialize wallet RPC if payment is required
        if ($requiredAmount > 0) {
            try {
                Log::info('Initializing Monero RPC - payment is required');
                $this->walletRPC = new walletRPC(
                    $config['host'],
                    $config['port'],
                    $config['ssl']
                );
            } catch (\Exception $e) {
                Log::error('Failed to initialize Monero RPC connection', [
                    'message' => $e->getMessage(),
                    'exception' => get_class($e),
                ]);
                $this->walletRPC = null;
            }
        } else {
            Log::info('Skipping Monero RPC initialization - payment is FREE');
            $this->walletRPC = null;
        }
    }

    /**
     * Show become vendor index page
     */
    public function index(Request $request): View
    {
        try {
            Log::debug('Loading become vendor index', ['user_id' => Auth::id()]);

            $user = $request->user();
            $hasPgpVerified = $user->pgpKey?->verified ?? false;
            $hasMoneroAddress = $user->returnAddresses()->exists();

            $vendorPayment = VendorPayment::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();

            return view('become-vendor.index', compact('hasPgpVerified', 'hasMoneroAddress', 'vendorPayment'));

        } catch (\Exception $e) {
            Log::error('Error loading become vendor index', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return view('become-vendor.index', [
                'hasPgpVerified' => false,
                'hasMoneroAddress' => false,
                'vendorPayment' => null,
            ])->with('error', 'An error occurred while loading the page.');
        }
    }

    /**
     * Show payment page for vendor application
     */
    public function payment(Request $request): View|RedirectResponse
    {
        try {
            Log::info('payment() method called', ['user_id' => Auth::id()]);

            $user = $request->user();
            $hasPgpVerified = $user->pgpKey?->verified ?? false;
            $hasMoneroAddress = $user->returnAddresses()->exists();

            // Check if user already has processed application
            $existingPayment = VendorPayment::where('user_id', $user->id)
                ->whereNotNull('application_status')
                ->first();

            if ($existingPayment) {
                Log::info('User already has processed application', ['user_id' => $user->id]);
                return redirect()->route('become.vendor')
                    ->with('info', 'You already have a processed vendor application.');
            }

            // If user is already vendor
            if ($user->isVendor()) {
                Log::info('User is already a vendor', ['user_id' => $user->id]);
                return view('become-vendor.payment', [
                    'alreadyVendor' => true,
                    'hasPgpVerified' => $hasPgpVerified,
                    'hasMoneroAddress' => $hasMoneroAddress,
                ]);
            }

            $requiredAmount = (float)config('monero.vendor_payment_required_amount');
            Log::info('Required amount check', ['amount' => $requiredAmount]);

            // Handle free payment
            if ($requiredAmount == 0) {
                return $this->handleFreePayment($user, $hasPgpVerified, $hasMoneroAddress);
            }

            // Handle paid payment with Monero
            Log::info('Payment is REQUIRED - proceeding with Monero payment');
            return $this->handlePaidPayment($user, $hasPgpVerified, $hasMoneroAddress);

        } catch (\Exception $e) {
            Log::error('Error in payment process', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return view('become-vendor.payment', [
                'error' => 'An error occurred while processing your payment. Please try again later.',
                'hasPgpVerified' => $request->user()->pgpKey?->verified ?? false,
                'hasMoneroAddress' => $request->user()->returnAddresses()->exists(),
            ]);
        }
    }

    /**
     * Handle free vendor payment
     */
    private function handleFreePayment(User $user, bool $hasPgpVerified, bool $hasMoneroAddress): View|RedirectResponse
    {
        try {
            Log::info('Handling free vendor payment', ['user_id' => $user->id]);

            $vendorPayment = VendorPayment::where('user_id', $user->id)
                ->where('payment_completed', true)
                ->whereNull('application_status')
                ->first();

            if (!$vendorPayment) {
                Log::info('Creating new free vendor payment record', ['user_id' => $user->id]);

                // Generate a placeholder address for free payments
                $placeholderAddress = 'FREE_' . strtoupper(Str::random(8));

                $vendorPayment = new VendorPayment([
                    'address' => $placeholderAddress,
                    'address_index' => 0,
                    'user_id' => $user->id,
                    'total_received' => 0,
                    'payment_completed' => true,
                    'expires_at' => Carbon::now()->addMinutes((int)config('monero.address_expiration_time')),
                ]);

                $vendorPayment->save();

                Log::info('Free vendor payment record created', [
                    'vendor_payment_id' => $vendorPayment->id,
                    'user_id' => $user->id,
                    'address' => $placeholderAddress,
                ]);
            }

            return redirect()->route('become.vendor.application');

        } catch (\Exception $e) {
            Log::error('Error creating free vendor payment', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('become.vendor')
                ->with('error', 'An error occurred while creating payment record.');
        }
    }

    /**
     * Handle paid vendor payment with Monero
     */
    private function handlePaidPayment(User $user, bool $hasPgpVerified, bool $hasMoneroAddress): View|RedirectResponse
    {
        try {
            $vendorPayment = $this->getCurrentVendorPayment($user);
            $qrCodeDataUri = $vendorPayment ? $this->generateQrCode($vendorPayment->address) : null;

            return view('become-vendor.payment', [
                'vendorPayment' => $vendorPayment,
                'qrCodeDataUri' => $qrCodeDataUri,
                'hasPgpVerified' => $hasPgpVerified,
                'hasMoneroAddress' => $hasMoneroAddress,
            ]);

        } catch (\Exception $e) {
            Log::error('Error in paid payment process', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);

            return view('become-vendor.payment', [
                'error' => 'An error occurred while processing your payment.',
                'hasPgpVerified' => $hasPgpVerified,
                'hasMoneroAddress' => $hasMoneroAddress,
            ]);
        }
    }

    /**
     * Get current vendor payment or create new one
     */
    private function getCurrentVendorPayment(User $user): VendorPayment|null
    {
        try {
            Log::info('getCurrentVendorPayment called', ['user_id' => $user->id]);

            $vendorPayment = VendorPayment::where('user_id', $user->id)
                ->where('expires_at', '>', Carbon::now())
                ->orderBy('created_at', 'desc')
                ->first();

            if ($vendorPayment) {
                Log::info('Found existing vendor payment', ['payment_id' => $vendorPayment->id]);
                $this->checkIncomingTransaction($vendorPayment);
                return $vendorPayment;
            }

            Log::info('No existing vendor payment, creating new one', ['user_id' => $user->id]);
            return $this->createVendorPayment($user);

        } catch (\Exception $e) {
            Log::error('Error getting current vendor payment', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Create new vendor payment with Monero address
     */
    private function createVendorPayment(User $user): VendorPayment
    {
        try {
            Log::info('createVendorPayment called', ['user_id' => $user->id]);

            if (!$this->walletRPC) {
                throw new \Exception('Monero RPC not initialized');
            }

            $result = $this->walletRPC->create_address(0, "Vendor Payment " . $user->id . "_" . time());

            Log::info('Monero RPC create_address response', [
                'address' => $result['address'] ?? 'N/A',
                'address_index' => $result['address_index'] ?? 'N/A',
            ]);

            $vendorPayment = new VendorPayment([
                'address' => $result['address'],
                'address_index' => $result['address_index'],
                'user_id' => $user->id,
                'expires_at' => Carbon::now()->addMinutes((int)config('monero.address_expiration_time')),
            ]);

            $vendorPayment->save();

            Log::info('Created new vendor payment address', [
                'payment_id' => $vendorPayment->id,
                'user_id' => $user->id,
                'address' => $result['address'],
            ]);

            return $vendorPayment;

        } catch (\Exception $e) {
            Log::error('Error creating Monero subaddress', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Check for incoming Monero transactions
     */
    private function checkIncomingTransaction(VendorPayment $vendorPayment): void
    {
        try {
            Log::info('checkIncomingTransaction called', ['payment_id' => $vendorPayment->id]);

            if (!$this->walletRPC) {
                Log::warning('Monero RPC not initialized, skipping transaction check');
                return;
            }

            $config = config('monero');
            $requiredAmount = (float)$config['vendor_payment_required_amount'];
            $minimumAmount = (float)$config['vendor_payment_minimum_amount'];

            Log::info('Payment config', [
                'required_amount' => $requiredAmount,
                'minimum_amount' => $minimumAmount,
            ]);

            $transfers = $this->walletRPC->get_transfers([
                'in' => true,
                'pool' => true,
                'subaddr_indices' => [$vendorPayment->address_index],
            ]);

            Log::debug('Monero transfers response received', [
                'has_in' => isset($transfers['in']),
                'has_pool' => isset($transfers['pool']),
            ]);

            $totalReceived = $this->calculateTotalReceived($transfers, $minimumAmount);

            Log::info('Total received from transfers', [
                'amount' => $totalReceived,
                'payment_id' => $vendorPayment->id,
            ]);

            $vendorPayment->total_received = $totalReceived;
            $vendorPayment->save();

            if ($totalReceived >= $requiredAmount && !$vendorPayment->payment_completed) {
                Log::info('Payment completed - marking as done', ['payment_id' => $vendorPayment->id]);
                $vendorPayment->payment_completed = true;
                $vendorPayment->save();
            }

        } catch (\Exception $e) {
            Log::error('Error checking incoming Monero transaction', [
                'payment_id' => $vendorPayment->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Calculate total received from transfers
     */
    private function calculateTotalReceived(array $transfers, float|int $minimumAmount): float
    {
        $totalReceived = 0.0;

        foreach (['in', 'pool'] as $type) {
            if (isset($transfers[$type]) && is_array($transfers[$type])) {
                foreach ($transfers[$type] as $transfer) {
                    $amount = $transfer['amount'] ?? 0;
                    if ($minimumAmount == 0 || $amount >= $minimumAmount * 1e12) {
                        $totalReceived += $amount / 1e12;
                    }
                }
            }
        }

        return $totalReceived;
    }

    /**
     * Show vendor application form
     */
    public function showApplication(): View|RedirectResponse
    {
        try {
            Log::info('showApplication() called', ['user_id' => Auth::id()]);

            $processedApplication = VendorPayment::where('user_id', Auth::id())
                ->whereNotNull('application_status')
                ->first();

            if ($processedApplication) {
                Log::info('User already has processed application');
                return redirect()->route('become.vendor')
                    ->with('info', 'You already have a processed vendor application.');
            }

            $vendorPayment = VendorPayment::where('user_id', Auth::id())
                ->where('payment_completed', true)
                ->whereNull('application_status')
                ->first();

            if (!$vendorPayment) {
                Log::error('No completed payment found', ['user_id' => Auth::id()]);
                return redirect()->route('become.vendor')
                    ->with('error', 'You must complete the payment before submitting an application.');
            }

            Log::info('Showing application form', ['user_id' => Auth::id()]);
            return view('become-vendor.application', compact('vendorPayment'));

        } catch (\Exception $e) {
            Log::error('Error showing application form', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('become.vendor')
                ->with('error', 'An error occurred while loading the application form.');
        }
    }

    /**
     * Submit vendor application
     */
    public function submitApplication(Request $request): RedirectResponse
    {
        try {
            Log::info('submitApplication() called', ['user_id' => Auth::id()]);

            $processedApplication = VendorPayment::where('user_id', Auth::id())
                ->whereNotNull('application_status')
                ->first();

            if ($processedApplication) {
                Log::info('User already has processed application');
                return redirect()->route('become.vendor')
                    ->with('info', 'You already have a processed vendor application.');
            }

            $vendorPayment = VendorPayment::where('user_id', Auth::id())
                ->where('payment_completed', true)
                ->whereNull('application_status')
                ->first();

            if (!$vendorPayment) {
                Log::error('No completed payment found');
                return redirect()->route('become.vendor')
                    ->with('error', 'You must complete the payment before submitting an application.');
            }

            // Validate request
            $validated = $request->validate([
                'application_text' => 'required|string|min:80|max:4000',
                'product_images' => [
                    'required',
                    'array',
                    'min:1',
                    'max:4',
                ],
                'product_images.*' => [
                    'required',
                    'file',
                    'image',
                    'max:800',
                    'mimes:jpeg,png,gif,webp',
                ],
            ]);

            // Process images
            $images = $this->processApplicationImages($request->file('product_images'));

            // Update vendor payment with application
            $vendorPayment->update([
                'application_text' => $validated['application_text'],
                'application_images' => json_encode($images),
                'application_status' => 'waiting',
                'application_submitted_at' => now(),
            ]);

            Log::info('Vendor application submitted', [
                'user_id' => Auth::id(),
                'payment_id' => $vendorPayment->id,
                'image_count' => count($images),
            ]);

            return redirect()->route('become.vendor')
                ->with('success', 'Your application has been submitted successfully and is now under review.');

        } catch (ValidationException $e) {
            Log::warning('Application validation error', [
                'user_id' => Auth::id(),
                'errors' => $e->validator->errors()->toArray(),
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator);

        } catch (\Exception $e) {
            Log::error('Error submitting vendor application', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while submitting your application. Please try again.');
        }
    }

    /**
     * Process application images
     *
     * @param array $files
     * @return array<int, string>
     */
    private function processApplicationImages(array $files): array
    {
        $images = [];

        foreach ($files as $file) {
            try {
                $images[] = $this->handleApplicationPictureUpload($file);
            } catch (\Exception $e) {
                // Rollback uploaded images on error
                foreach ($images as $uploadedImage) {
                    Storage::disk('private')->delete('vendor_application_pictures/' . $uploadedImage);
                }

                Log::error('Failed to upload application image', [
                    'message' => $e->getMessage(),
                ]);

                throw new \Exception('Failed to upload images. Please try again.');
            }
        }

        return $images;
    }

    /**
     * Handle single application picture upload
     */
    private function handleApplicationPictureUpload(\Illuminate\Http\UploadedFile $file): string
    {
        try {
            Log::debug('Processing application picture upload', [
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
            ]);

            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($file->getPathname());

            $allowedMimeTypes = [
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/webp',
            ];

            if (!in_array($mimeType, $allowedMimeTypes)) {
                throw new \Exception('Invalid file type. Allowed types are JPEG, PNG, GIF, and WebP.');
            }

            $extension = match ($mimeType) {
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                'image/webp' => 'webp',
                default => 'jpg',
            };

            $filename = time() . '_' . Str::uuid() . '.' . $extension;

            $manager = new ImageManager(new GdDriver());
            $image = $manager->read($file)
                ->resize(800, 800, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

            $encodedImage = match ($mimeType) {
                'image/png' => $image->encode(new PngEncoder()),
                'image/webp' => $image->encode(new WebpEncoder()),
                'image/gif' => $image->encode(new GifEncoder()),
                default => $image->encode(new JpegEncoder(80)),
            };

            if (!Storage::disk('private')->put('vendor_application_pictures/' . $filename, (string)$encodedImage)) {
                throw new \Exception('Failed to save application picture to storage');
            }

            Log::info('Application picture uploaded successfully', [
                'filename' => $filename,
                'mime_type' => $mimeType,
            ]);

            return $filename;

        } catch (NotReadableException $e) {
            Log::error('Image processing failed', [
                'message' => $e->getMessage(),
            ]);

            throw new \Exception('Failed to process uploaded image. Please try a different image.');

        } catch (\Exception $e) {
            Log::error('Application picture upload failed', [
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Generate QR code data URI from address
     */
    private function generateQrCode(string $address): string|null
    {
        try {
            Log::debug('Generating QR code', ['address_length' => strlen($address)]);

            $result = Builder::create()
                ->writer(new PngWriter())
                ->writerOptions([])
                ->data($address)
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(ErrorCorrectionLevel::High)
                ->size(300)
                ->margin(10)
                ->build();

            return $result->getDataUri();

        } catch (\Exception $e) {
            Log::error('Error generating QR code', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
