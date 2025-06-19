<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Exception;

class UploadService
{
    /**
     * Upload an image file to S3 under the specified folder with date-based subfolder.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string URL of the uploaded file
     * @throws Exception
     */
    public function uploadImage(UploadedFile $file, string $folder): string
    {
        try {
            $dateFolder = now()->format('Y-m-d');
            $fullFolderPath = $folder . '/' . $dateFolder;
            $filename = uniqid() . '_' . trim($file->getClientOriginalName());

            $path = $file->storeAs($fullFolderPath, $filename, 's3');

            if (!$path) {
                throw new Exception('File upload to S3 failed.');
            }

            return Storage::disk('s3')->url($path);

        } catch (Exception $e) {
            throw new Exception('Upload failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete a file from S3 given its URL.
     *
     * @param string $url
     * @return void
     * @throws Exception
     */
    public function deleteImage(string $url): void
    {
        try {
            // Parse the path from the URL
            $parsedUrl = parse_url($url);
            if (!isset($parsedUrl['path'])) {
                throw new Exception('Invalid URL provided for deletion.');
            }

            // Remove leading slash from path
            $path = ltrim($parsedUrl['path'], '/');

            $deleted = Storage::disk('s3')->delete($path);

            if (!$deleted) {
                throw new Exception('Failed to delete file from S3.');
            }
        } catch (Exception $e) {
            throw new Exception('Delete error: ' . $e->getMessage());
        }
    }
}
