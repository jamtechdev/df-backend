<?php

namespace App\Services;

use App\Models\MediaTranslation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class MediaTranslationService
{
    /**
     * Create a new media translation.
     *
     * @param int $mediaId
     * @param array $data
     * @return MediaTranslation
     */
    public function createTranslation(int $mediaId, array $data): MediaTranslation
    {
        $data['media_id'] = $mediaId;
        return MediaTranslation::create($data);
    }

    /**
     * Get a media translation by ID.
     *
     * @param int $mediaTranslationId
     * @return MediaTranslation
     *
     * @throws ModelNotFoundException
     */
    public function getTranslation(int $mediaTranslationId): MediaTranslation
    {
        return MediaTranslation::findOrFail($mediaTranslationId);
    }

    /**
     * Update a media translation.
     *
     * @param int $mediaTranslationId
     * @param array $data
     * @return MediaTranslation
     *
     * @throws ModelNotFoundException
     */
    public function updateTranslation(int $mediaTranslationId, array $data): MediaTranslation
    {
        return DB::transaction(function () use ($mediaTranslationId, $data) {
            $translation = MediaTranslation::findOrFail($mediaTranslationId);
            $translation->update($data);
            return $translation;
        });
    }

    /**
     * Delete a media translation.
     *
     * @param int $mediaTranslationId
     * @return void
     *
     * @throws ModelNotFoundException
     */
    public function deleteTranslation(int $mediaTranslationId): void
    {
        DB::transaction(function () use ($mediaTranslationId) {
            $translation = MediaTranslation::findOrFail($mediaTranslationId);
            $translation->delete();
        });
    }
}
