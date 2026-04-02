<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Guest Profile Controller - Public Profile Picture Serving
 * =========================================================================
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Exception;
use finfo;

class GuestProfileController extends Controller
{
    /**
     * Allowed MIME types for profile pictures
     *
     * @var array<string>
     */
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    /**
     * Get profile picture for guest users (No authentication required)
     * Used to display vendor profile pictures on product pages for guests
     *
     * @param string $filename
     * @return Response
     */
    public function getProfilePicture(string $filename): Response
    {
        try {
            Log::debug('Guest accessing profile picture', [
                'filename' => substr($filename, 0, 20) . '...',
                'ip' => request()->ip(),
            ]);

            // Extract just the filename if a full path is passed
            $filename = basename($filename);

            // If it's the default picture or empty, serve it from public directory
            if ($filename === 'default-profile-picture.png' || empty($filename)) {
                Log::debug('Serving default profile picture');
                return response()->file(public_path('images/default-profile-picture.png'));
            }

            $path = 'profile_pictures/' . $filename;

            // Check if file exists in private storage
            if (!Storage::disk('private')->exists($path)) {
                Log::debug('Profile picture not found in storage, serving default', [
                    'filename' => $filename,
                ]);
                return response()->file(public_path('images/default-profile-picture.png'));
            }

            // Get the file content
            $file = Storage::disk('private')->get($path);

            // Verify file type using finfo
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($file);

            // Validate MIME type
            if (!in_array($mimeType, self::ALLOWED_MIME_TYPES)) {
                Log::warning('Invalid MIME type for profile picture', [
                    'filename' => $filename,
                    'mime_type' => $mimeType,
                    'ip' => request()->ip(),
                ]);
                return response()->file(public_path('images/default-profile-picture.png'));
            }

            // Create the response with caching headers
            $response = Response::make($file, 200);
            $response->header('Content-Type', $mimeType);
            $response->header('Cache-Control', 'public, max-age=31536000');

            Log::debug('Profile picture served successfully', [
                'filename' => substr($filename, 0, 20) . '...',
                'mime_type' => $mimeType,
            ]);

            return $response;

        } catch (Exception $e) {
            Log::error('Failed to retrieve guest profile picture', [
                'filename' => $filename,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => request()->ip(),
            ]);

            // If the picture is not found or invalid, return the default picture
            return response()->file(public_path('images/default-profile-picture.png'));
        }
    }
}
