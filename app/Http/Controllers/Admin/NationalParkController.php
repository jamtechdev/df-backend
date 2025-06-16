<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NationalParkService;
use App\Models\NationalPark;
use App\Models\Category;
use App\Models\Theme;
use Illuminate\Support\Str;

class NationalParkController extends Controller
{
    protected $nationalParkService;

    public function __construct(NationalParkService $nationalParkService)
    {
        $this->nationalParkService = $nationalParkService;
    }

    private function generateUniqueSlug($name, $id = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (NationalPark::where('slug', $slug)->when($id, function ($query) use ($id) {
            return $query->where('id', '!=', $id);
        })->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function index()
    {
        try {
            $categories = Category::all();
            $themes = Theme::all();
            return view('admin.national-park.index', compact('categories', 'themes'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load national parks index: ' . $e->getMessage()], 500);
        }
    }

    public function fetchData()
    {
        try {
            $nationalParks = $this->nationalParkService->getAllNationalParks();
            return response()->json(['nationalParks' => $nationalParks]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch national parks: ' . $e->getMessage()], 500);
        }
    }

    public function create()
    {
        try {
            $categories = Category::all();
            $themes = Theme::all();
            return view('admin.national-park.create', compact('categories', 'themes'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load create national park form: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'theme_id' => 'required|exists:themes,id',
                'name' => 'required|string|max:255',
                'seo_title' => 'nullable|string|max:255',
                'seo_description' => 'nullable|string|max:255',
                'seo_keywords' => 'nullable|string|max:255',
                'is_featured' => 'nullable|boolean',
            ]);

            $validated['slug'] = $this->generateUniqueSlug($validated['name']);

            $nationalPark = \App\Models\NationalPark::create($validated);

            return response()->json(['message' => 'National Park created successfully', 'nationalPark' => $nationalPark]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create national park: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        try {
            $nationalPark = $this->nationalParkService->getNationalParkById($id);
            $categories = Category::all();
            $themes = Theme::all();

            if (request()->ajax()) {
                return response()->json([
                    'nationalPark' => $nationalPark,
                    'categories' => $categories,
                    'themes' => $themes,
                ]);
            }

            return view('admin.national-park.edit', compact('nationalPark', 'categories', 'themes'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch national park data: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $nationalPark = \App\Models\NationalPark::findOrFail($id);

            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'theme_id' => 'required|exists:themes,id',
                'name' => 'required|string|max:255',
                'seo_title' => 'nullable|string|max:255',
                'seo_description' => 'nullable|string|max:255',
                'seo_keywords' => 'nullable|string|max:255',
                'is_featured' => 'nullable|boolean',
            ]);

            $validated['slug'] = $this->generateUniqueSlug($validated['name'], $id);

            $nationalPark->update($validated);

            return response()->json(['message' => 'National Park updated successfully', 'nationalPark' => $nationalPark]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update national park: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $nationalPark = \App\Models\NationalPark::findOrFail($id);
            $nationalPark->delete();

            return response()->json(['message' => 'National Park deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete national park: ' . $e->getMessage()], 500);
        }
    }

    public function fetchCategoriesAndThemes()
    {
        try {
            $categories = Category::with('translations')->get();
            $themes = Theme::all();
            return response()->json([
                'categories' => $categories,
                'themes' => $themes,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch categories and themes: ' . $e->getMessage()], 500);
        }
    }
}
