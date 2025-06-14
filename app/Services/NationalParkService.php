<?php

namespace App\Services;

use App\Models\NationalPark;

class NationalParkService
{
    public function getAllNationalParks()
    {
        return NationalPark::with(['category.translations', 'theme'])->get();
    }

    public function getNationalParkById($id)
    {
        return NationalPark::findOrFail($id);
    }

    public function createNationalPark(array $data)
    {
        return NationalPark::create($data);
    }

    public function updateNationalPark(NationalPark $nationalPark, array $data)
    {
        $nationalPark->update($data);
        return $nationalPark;
    }

    public function deleteNationalPark(NationalPark $nationalPark)
    {
        $nationalPark->delete();
    }
}
