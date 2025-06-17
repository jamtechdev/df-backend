<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentBlock;
use App\Models\NationalParkTranslation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContentBlockController extends Controller
{
    public function index($national_park_id, $np_translation_id)
    {
        return view('admin.national-park.content-blocks.index', compact('np_translation_id', 'national_park_id'));
    }

    public function fetchData(Request $request, $np_translation_id)
    {
        $query = ContentBlock::orderBy('sort_order');

        if ($np_translation_id) {
            $query->where('national_park_translation_id', $np_translation_id);
        }

        $contentBlocks = $query->get();
        return response()->json($contentBlocks);
    }


    public function store(Request $request,$national_park_id, $np_translation_id)
    {
        $validated = $request->validate([
            'section_type' => ['required', Rule::in(['key_feature', 'explore', 'other', 'journey'])],
            'heading' => 'nullable|string|max:255',
            'subheading' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['national_park_translation_id'] = $np_translation_id;

        $contentBlock = ContentBlock::create($validated);

        return response()->json(['message' => 'Content block created successfully', 'contentBlock' => $contentBlock]);
    }

    public function update(Request $request,$national_park_id ,$np_translation_id, $id)
    {
        $content_block = ContentBlock::findOrFail($id); // use findOrFail

        $validated = $request->validate([
            'section_type' => ['required', Rule::in(['key_feature', 'explore', 'other', 'journey'])],
            'heading' => 'nullable|string|max:255',
            'subheading' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['national_park_translation_id'] = $np_translation_id;

        $content_block->update($validated);

        return response()->json(['message' => 'Content block updated successfully', 'contentBlock' => $content_block]);
    }

    public function destroy($np_translation_id, $id)
    {
        $content_block = ContentBlock::findOrFail($id); // use findOrFail

        $content_block->delete();

        return response()->json(['message' => 'Content block deleted successfully']);
    }
}
