<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    public string $disk;
    public function __construct($disk = 'public')
    {
        $this->disk = $disk;
    }

    public function uploadFile(Request $request, $field_name, $upload_path = 'images', $delete_path = null)
    {

        // Upload image
        if ($request->hasFile($field_name)) {

            // Delete old file
            if ($delete_path) {
                $this->delete($delete_path);
            }
            // Upload new file
            return $request->file($field_name)->store(
                $upload_path,
                $this->disk
            );
        }
    }

    public function delete($path = '')
    {
        Storage::disk($this->disk)->delete($path);
    }
}
