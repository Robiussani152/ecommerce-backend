<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('apiJsonResponse')) {
    function apiJsonResponse($status = 'success', $data = null, $message = '', $statusCode = 200)
    {
        return response()
            ->json([
                'status' => $status,
                'data' => $data ?? [],
                'message' => $message
            ], $statusCode);
    }
}

if (!function_exists('storageLink')) {
    function storageLink($url)
    {
        $defaultImage = 'images/logo.png';
        if ($url && Storage::disk('public')->exists($url)) {
            return Storage::url($url);
        } else {
            return Storage::url($defaultImage);
        }
    }
}
