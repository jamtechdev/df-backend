<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\HiddenWonderDataTable;
use App\Http\Controllers\Controller;
use App\Models\HiddenWonder;
use App\Services\HiddenWonderService;
use Illuminate\Http\Request;

class HiddenWonderController extends Controller
{
    protected $hiddenWonderService;

    public function __construct(HiddenWonderService $hiddenWonderService)
    {
        $this->hiddenWonderService = $hiddenWonderService;
    }

    public function index(HiddenWonderDataTable $dataTable, $national_park_translation_id = null)
    {
        return $dataTable->render('admin.hidden_wonders.index', compact('national_park_translation_id'));
    }

    public function create()
    {
        return view('admin.hidden_wonders.form');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'section_heading' => 'nullable|string|max:255',
            'section_title' => 'nullable|string|max:255',
            'section_subtitle' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'tip_heading' => 'nullable|string|max:255',
            'tip_text' => 'nullable|string',
            'quote' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'required|boolean',
        ]);

        try {
            $validated['national_park_translation_id'] = $request->national_park_translation_id;
            $hiddenWonder = $this->hiddenWonderService->createHiddenWonder($validated);
            return response()->json(['message' => 'Hidden Wonder created successfully.', 'hidden_wonder' => $hiddenWonder]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Creation failed: ' . $e->getMessage()], 500);
        }
    }

    public function edit(Request $request, HiddenWonder $hiddenWonder)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['hiddenWonder' => $hiddenWonder]);
        }
        return view('admin.hidden_wonders.form', compact('hiddenWonder'));
    }

    public function update(Request $request, HiddenWonder $hiddenWonder)
    {
        $validated = $request->validate([

            'section_heading' => 'nullable|string|max:255',
            'section_title' => 'nullable|string|max:255',
            'section_subtitle' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'tip_heading' => 'nullable|string|max:255',
            'tip_text' => 'nullable|string',
            'quote' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'required|boolean',
        ]);

        try {
            $updated = $this->hiddenWonderService->updateHiddenWonder($hiddenWonder, $validated);
            return response()->json(['message' => 'Hidden Wonder updated successfully.', 'hidden_wonder' => $updated]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Update failed: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(HiddenWonder $hiddenWonder)
    {
        try {
            $this->hiddenWonderService->deleteHiddenWonder($hiddenWonder);
            return response()->json(['message' => 'Hidden Wonder deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Delete failed: ' . $e->getMessage()], 500);
        }
    }
}
