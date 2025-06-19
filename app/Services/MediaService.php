<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Support\Facades\DB;
use Exception;

class MediaService
{
    /**
     * Get all media records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllMedia()
    {
        return Media::all();
    }

    /**
     * Create and save a new media record.
     *
     * @param array $data
     * @return Media
     * @throws Exception
     */
    public function createMedia(array $data): Media
    {
        return DB::transaction(function () use ($data) {
            return Media::create($data);
        });
    }

    /**
     * Update an existing media record.
     *
     * @param Media $media
     * @param array $data
     * @return Media
     * @throws Exception
     */
    public function updateMedia(Media $media, array $data): Media
    {
        return DB::transaction(function () use ($media, $data) {
            $media->update($data);
            return $media;
        });
    }

    /**
     * Delete an existing media record.
     *
     * @param Media $media
     * @return bool|null
     * @throws Exception
     */
    public function deleteMedia(Media $media): ?bool
    {
        return DB::transaction(function () use ($media) {
            return $media->delete();
        });
    }
}
