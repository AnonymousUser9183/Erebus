<?php

/*
 * =========================================================================
 * © 2026 Erebus Development Team
 * Author: AnonymousUser9183
 * =========================================================================
 * Private File Controller
 * =========================================================================
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PrivateFileController extends Controller
{
    /**
     * Serve private product pictures
     */
    public function productPicture(string $filename)
    {
        $path = 'product_pictures/' . basename($filename);
        
        // Check if file exists
        if (!Storage::disk('private')->exists($path)) {
            abort(404, 'Product image not found');
        }
        
        // Get full path
        $fullPath = Storage::disk('private')->path($path);
        
        // Serve file with proper headers
        return response()->file($fullPath, [
            'Content-Type' => Storage::disk('private')->mimeType($path),
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }

    /**
     * Serve guest product pictures
     */
    public function guestProductPicture(string $filename)
    {
        $path = 'product_pictures/' . basename($filename);
        
        if (!Storage::disk('private')->exists($path)) {
            abort(404, 'Product image not found');
        }
        
        $fullPath = Storage::disk('private')->path($path);
        
        return response()->file($fullPath, [
            'Content-Type' => Storage::disk('private')->mimeType($path),
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }

    /**
     * Serve profile pictures
     */
    public function profilePicture(string $filename)
    {
        $path = 'profile_pictures/' . basename($filename);
        
        if (!Storage::disk('private')->exists($path)) {
            abort(404, 'Profile image not found');
        }
        
        $fullPath = Storage::disk('private')->path($path);
        
        return response()->file($fullPath, [
            'Content-Type' => Storage::disk('private')->mimeType($path),
            'Cache-Control' => 'public, max-age=2592000',
        ]);
    }
}
