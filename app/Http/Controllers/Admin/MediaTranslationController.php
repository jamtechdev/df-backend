<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\MediaTranslationDataTable;
use App\Http\Controllers\Controller;
use App\Services\MediaTranslationService;
use Illuminate\Http\Request;

class MediaTranslationController extends Controller
{
    protected $mediaTranslationService;

    public function __construct(MediaTranslationService $mediaTranslationService)
    {
        $this->mediaTranslationService = $mediaTranslationService;
    }

    public function index(MediaTranslationDataTable $dataTable, $mediaId)
    {
        $media = \App\Models\Media::findOrFail($mediaId);
        $dataTable->setMediaId($mediaId);
        return $dataTable->render('admin.media.translations.index', compact('mediaId', 'media'));
    }

    public function store(Request $request, $mediaId)
    {
        try {
            $validated = $request->validate([
                'language_code' => 'required|string|max:10',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'nullable|string|max:50',
                'overlay_title' => 'nullable|string|max:255',
                'overlay_subtitle' => 'nullable|string|max:255',
                'overlay_description' => 'nullable|string',
                'subtitle' => 'nullable|string|max:255',
            ]);

            $this->mediaTranslationService->createTranslation($mediaId, $validated);

            return response()->json(['success' => true, 'message' => 'Translation created successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Create failed: ' . $e->getMessage()], 400);
        }
    }

    public function edit($mediaTranslationId)
    {
        try {
            $translation = $this->mediaTranslationService->getTranslation($mediaTranslationId);
            return response()->json(['success' => true, 'data' => $translation]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to load translation: ' . $e->getMessage()], 400);
        }
    }

    public function updateTranslation(Request $request, $mediaTranslationId)
    {
        try {
            $validated = $request->validate([
                'language_code' => 'required|string|max:10',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'nullable|string|max:50',
                'overlay_title' => 'nullable|string|max:255',
                'overlay_subtitle' => 'nullable|string|max:255',
                'overlay_description' => 'nullable|string',
                'subtitle' => 'nullable|string|max:255',
            ]);

            $this->mediaTranslationService->updateTranslation($mediaTranslationId, $validated);

            return response()->json(['success' => true, 'message' => 'Translation updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Update failed: ' . $e->getMessage()], 400);
        }
    }

    public function destroy($mediaTranslationId)
    {
        try {
            $this->mediaTranslationService->deleteTranslation($mediaTranslationId);

            return response()->json(['success' => true, 'message' => 'Translation deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Delete failed: ' . $e->getMessage()], 400);
        }
    }
}
