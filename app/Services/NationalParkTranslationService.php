<?php

namespace App\Services;

use App\Models\NationalParkTranslation;
use Exception;

class NationalParkTranslationService
{
    /**
     * Fetch translations optionally filtered by national park ID.
     *
     * @param int|null $national_park_id
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws Exception
     */
    public function fetchData($national_park_id = null)
    {
        try {
            $query = NationalParkTranslation::query();

            if (!is_null($national_park_id)) {
                $query->where('national_park_id', $national_park_id);
            }

            return $query->with('nationalPark','theme')->get();
        } catch (Exception $e) {
            throw new Exception('Failed to fetch translations: ' . $e->getMessage());
        }
    }

    /**
     * Create a new translation.
     *
     * @param array $data
     * @return NationalParkTranslation
     * @throws Exception
     */
    public function createTranslation(array $data)
    {
        try {
            return NationalParkTranslation::create($data);
        } catch (Exception $e) {
            throw new Exception('Failed to create translation: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing translation.
     *
     * @param int $id
     * @param array $data
     * @return NationalParkTranslation
     * @throws Exception
     */
    public function updateTranslation($id, array $data)
    {
        try {
            $translation = NationalParkTranslation::findOrFail($id);
            $translation->update($data);
            return $translation;
        } catch (Exception $e) {
            throw new Exception('Failed to update translation: ' . $e->getMessage());
        }
    }

    /**
     * Delete a translation.
     *
     * @param int $id
     * @return void
     * @throws Exception
     */
    public function deleteTranslation($id)
    {
        try {
            $translation = NationalParkTranslation::findOrFail($id);
            $translation->delete();
        } catch (Exception $e) {
            throw new Exception('Failed to delete translation: ' . $e->getMessage());
        }
    }

    /**
     * Get translations by national park ID.
     *
     * @param int $national_park_id
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws Exception
     */
    public function getByNationalParkId($national_park_id)
    {
        try {
            return NationalParkTranslation::where('national_park_id', $national_park_id)->get();
        } catch (Exception $e) {
            throw new Exception('Failed to get translations by national park ID: ' . $e->getMessage());
        }
    }
}
