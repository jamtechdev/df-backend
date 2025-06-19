<?php

namespace App\Services;

use App\Models\HiddenWonder;
use Illuminate\Support\Facades\DB;
use Exception;

class HiddenWonderService
{
    /**
     * Get all hidden wonders.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllHiddenWonders()
    {
        return HiddenWonder::all();
    }

    /**
     * Create and save a new hidden wonder.
     *
     * @param array $data
     * @return HiddenWonder
     * @throws Exception
     */
    public function createHiddenWonder(array $data): HiddenWonder
    {
        return DB::transaction(function () use ($data) {
            return HiddenWonder::create($data);
        });
    }

    /**
     * Update an existing hidden wonder.
     *
     * @param HiddenWonder $hiddenWonder
     * @param array $data
     * @return HiddenWonder
     * @throws Exception
     */
    public function updateHiddenWonder(HiddenWonder $hiddenWonder, array $data): HiddenWonder
    {
        return DB::transaction(function () use ($hiddenWonder, $data) {
            $hiddenWonder->update($data);
            return $hiddenWonder;
        });
    }

    /**
     * Delete an existing hidden wonder.
     *
     * @param HiddenWonder $hiddenWonder
     * @return bool|null
     * @throws Exception
     */
    public function deleteHiddenWonder(HiddenWonder $hiddenWonder): ?bool
    {
        return DB::transaction(function () use ($hiddenWonder) {
            return $hiddenWonder->delete();
        });
    }
}
