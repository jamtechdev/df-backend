<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Support\Facades\DB;

class MediaService
{
    public function getAllMedia()
    {
        return Media::all();
    }

    public function createMedia(array $data): Media
    {
        return Media::create($data);
    }

    public function updateMedia(Media $media, array $data): Media
    {
        $media->update($data);
        return $media;
    }

    public function deleteMedia(Media $media): void
    {
        $media->delete();
    }
}
