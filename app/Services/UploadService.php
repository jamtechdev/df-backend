<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Exception;

class UploadService
{
    /**
     * Upload an image file to S3 under the specified folder with date-based subfolder.
     * If a previous file URL is provided, delete that file first.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param string|null $previousUrl // Optional: previous file URL to delete
     * @return string URL of the uploaded file
     * @throws Exception
     */
    public function uploadImage(UploadedFile $file, string $folder, ?string $previousUrl = null): string
    {
        try {
            // If previous URL provided, delete the old file
            if ($previousUrl) {
                $this->deleteImage($previousUrl);
            }

            // Create date-based subfolder (e.g., 2025-06-16)
            $dateFolder = now()->format('Y-m-d');

            // Combine main folder and date subfolder
            $fullFolderPath = $folder . '/' . $dateFolder;

            // Generate a unique filename
            $filename = uniqid() . '_' . trim($file->getClientOriginalName());

            // Store the file in S3 with the full folder path
            $path = $file->storeAs($fullFolderPath, $filename, 's3');

            if (!$path) {
                throw new Exception('Failed to upload file to S3.');
            }

            // Return the full URL to the uploaded file
            return Storage::disk('s3')->url($path);

        } catch (Exception $e) {
            throw new Exception('Upload error: ' . $e->getMessage());
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
