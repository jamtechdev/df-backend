<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NationalParkService;
use App\Models\NationalPark;
use App\Models\Category;
use App\Models\Theme;

class NationalParkController extends Controller
{
    protected $nationalParkService;

    public function __construct(NationalParkService $nationalParkService)
    {
        $this->nationalParkService = $nationalParkService;
    }

    public function index()
    {
        $categories = Category::all();
        $themes = Theme::all();
        return view('admin.national_park.index', compact('categories', 'themes'));
    }

    public function fetchData()
    {
        $nationalParks = $this->nationalParkService->getAllNationalParks();
        return response()->json(['nationalParks' => $nationalParks]);
    }

    public function create()
    {
        $categories = Category::all();
        $themes = Theme::all();
        return view('admin.national_park.create', compact('categories', 'themes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'theme_id' => 'required|exists:themes,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:national_parks,slug',
        ]);

        $nationalPark = \App\Models\NationalPark::create($validated);

        return response()->json(['message' => 'National Park created successfully', 'nationalPark' => $nationalPark]);
    }

    public function edit($id)
    {
        $nationalPark = $this->nationalParkService->getNationalParkById($id);
        $categories = Category::all();
        $themes = Theme::all();
        return view('admin.national_park.edit', compact('nationalPark', 'categories', 'themes'));
    }

    public function update(Request $request, $id)
    {
        $nationalPark = \App\Models\NationalPark::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'theme_id' => 'required|exists:themes,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:national_parks,slug,' . $id,
        ]);

        $nationalPark->update($validated);

        return response()->json(['message' => 'National Park updated successfully', 'nationalPark' => $nationalPark]);
    }

    public function destroy($id)
    {
        $nationalPark = \App\Models\NationalPark::findOrFail($id);
        $nationalPark->delete();

        return response()->json(['message' => 'National Park deleted successfully']);
    }
}
