<?php

use Illuminate\Database\Eloquent\Model;
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
            return asset(Storage::url($url));
        } else {
            return asset(Storage::url($defaultImage));
        }
    }
}

if (!function_exists('prefixGenerator')) {
    function prefixGenerator(Model $model, $prefix = 'WD-')
    {
        $countNumber = getRandomNumber(6);
        return $prefix . sprintf('%07d', $countNumber + 1);
    }
}

if (!function_exists('getRandomNumber')) {
    function getRandomNumber($length = 8)
    {
        $characters = '0123456789';
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        return $string;
    }
}
