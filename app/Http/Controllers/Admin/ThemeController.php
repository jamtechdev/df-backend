<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\ThemesDataTable;
use App\Models\Theme;
use App\Services\ThemeService;
use Illuminate\Support\Str;

class ThemeController extends Controller
{
    protected $themeService;

    public function __construct(ThemeService $themeService)
    {
        $this->themeService = $themeService;
    }

    public function index(ThemesDataTable $dataTable)
    {
        $themes = Theme::all();
        return $dataTable->render('admin.theme.index', compact('themes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'root' => 'nullable|string',
        ]);

        $this->themeService->store($validated);

        return response()->json(['message' => 'Theme created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $theme = Theme::find($id);

        if (!$theme) {
            return response()->json(['message' => 'Theme not found.'], 404);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'root' => 'nullable|string|max:255',
        ]);

        $theme->update($data);

        return response()->json(['message' => 'Theme updated successfully.']);
    }


    public function destroy($id)
    {
        $theme = Theme::find($id);

        if (!$theme) {
            return response()->json(['message' => 'Theme not found.'], 404);
        }

        $theme->delete();

        return response()->json(['message' => 'Theme deleted successfully.']);
    }
}
