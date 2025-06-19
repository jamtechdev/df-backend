<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\MediaTranslationDataTable;
use App\Http\Controllers\Controller;
use App\Models\MediaTranslation;
use Illuminate\Http\Request;

class MediaTranslationController extends Controller
{
    public function index(MediaTranslationDataTable $dataTable, $mediaId)
    {
        $media = \App\Models\Media::findOrFail($mediaId);
        $dataTable->setMediaId($mediaId);
        return $dataTable->render('admin.media.translations.index', compact('mediaId', 'media'));
    }

    public function edit($mediaTranslationId)
    {
        try {
            $translation = MediaTranslation::findOrFail($mediaTranslationId);
            return view('admin.media.translations.edit', compact('translation'));
        } catch (\Exception $e) {
            return back()->withErrors('Failed to load translation: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $mediaTranslationId)
    {
        try {
            $validated = $request->validate([
                'language' => 'required|string|max:10',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $translation = MediaTranslation::findOrFail($mediaTranslationId);
            $translation->update($validated);

            return redirect()->route('media.translations.index', ['media' => $translation->media_id])
                ->with('success', 'Translation updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors('Update failed: ' . $e->getMessage());
        }
    }

    public function destroy($mediaTranslationId)
    {
        try {
            $translation = MediaTranslation::findOrFail($mediaTranslationId);
            $mediaId = $translation->media_id;
            $translation->delete();

            return redirect()->route('media.translations.index', ['media' => $mediaId])
                ->with('success', 'Translation deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors('Delete failed: ' . $e->getMessage());
        }
    }
}
