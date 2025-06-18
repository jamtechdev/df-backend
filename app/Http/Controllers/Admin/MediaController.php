<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\MediaDataTable;
use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\NationalPark;
use App\Models\NationalParkTranslation;
use Illuminate\Http\Request;
use App\Services\MediaService;

class MediaController extends Controller
{
    protected $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function index(MediaDataTable $dataTable, $park)
    {
        // Set parkId manually since Laravel cannot inject constructor param
        $dataTable->setparkId($park);

        return $dataTable->render('admin.media.index');
    }



    public function create($translation = null)
    {
        try {
            $translation = NationalParkTranslation::findOrFail($translation);
            return view('admin.media.form', compact('translation'));
        } catch (\Exception $e) {
            return back()->withErrors('Failed to load create form: ' . $e->getMessage());
        }
    }




    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'mediable_id' => 'required|integer',
                'mediable_type' => 'required|string|max:255',
                'type' => 'nullable|string|max:255',
                's3_bucket' => 'nullable|string|max:255',
                's3_url' => 'nullable|string|max:255',
                'file_size' => 'nullable|integer',
                'mime_type' => 'nullable|string|max:255',
                'dimensions' => 'nullable|json',
                'sort_order' => 'nullable|integer',
                'metadata' => 'nullable|json',
                'is_gallery_visual' => 'boolean',
                'uploaded_by' => 'nullable|integer',
            ]);

            $media = $this->mediaService->createMedia($validated);

            return response()->json(['message' => 'Media created successfully', 'media' => $media]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create media: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        try {
            $media = Media::findOrFail($id);
            return response()->json(['media' => $media]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch media data: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $media = Media::findOrFail($id);

            $validated = $request->validate([
                'mediable_id' => 'required|integer',
                'mediable_type' => 'required|string|max:255',
                'type' => 'nullable|string|max:255',
                's3_bucket' => 'nullable|string|max:255',
                's3_url' => 'nullable|string|max:255',
                'file_size' => 'nullable|integer',
                'mime_type' => 'nullable|string|max:255',
                'dimensions' => 'nullable|json',
                'sort_order' => 'nullable|integer',
                'metadata' => 'nullable|json',
                'is_gallery_visual' => 'boolean',
                'uploaded_by' => 'nullable|integer',
            ]);

            $media = $this->mediaService->updateMedia($media, $validated);

            return response()->json(['message' => 'Media updated successfully', 'media' => $media]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update media: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $media = Media::findOrFail($id);
            $this->mediaService->deleteMedia($media);
            return response()->json(['message' => 'Media deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete media: ' . $e->getMessage()], 500);
        }
    }
}
