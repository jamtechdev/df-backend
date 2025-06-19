<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\MediaDataTable;
use App\Http\Controllers\Controller;
use App\Models\NationalPark;
use App\Models\Media;
use App\Services\MediaService;
use App\Services\UploadService;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    protected $mediaService;
    protected $uploadService;

    public function __construct(MediaService $mediaService, UploadService $uploadService)
    {
        $this->mediaService = $mediaService;
        $this->uploadService = $uploadService;
    }

    public function index(MediaDataTable $dataTable, $park)
    {
        $dataTable->setparkId($park);
        return $dataTable->render('admin.media.index');
    }

    public function create($park = null)
    {
        try {
            $park = NationalPark::findOrFail($park);
            return view('admin.media.form', compact('park'));
        } catch (\Exception $e) {
            return back()->withErrors('Failed to load create form: ' . $e->getMessage());
        }
    }

    public function store(Request $request, $parkId)
    {
        try {
            $validated = $request->validate([
                'files' => 'required|array|min:1',
                'files.*' => 'file|mimes:jpeg,png,jpg,gif,webp|max:5120',
            ]);

            $folder = 'national-park/' . $parkId;
            $uploadedBy = auth()->id() ?? 1;

            $park = NationalPark::findOrFail($parkId);
            $createdMedia = [];

            foreach ($validated['files'] as $file) {
                $url = $this->uploadService->uploadImage($file, $folder);

                $mediaData = [
                    'mediable_id'       => $park->id,
                    'mediable_type'     => NationalPark::class,
                    's3_bucket'         => config('filesystems.disks.s3.bucket'),
                    's3_url'            => $url,
                    'file_size'         => $file->getSize(),
                    'mime_type'         => $file->getMimeType(),
                    'type'              => 'gallery_image',
                    'sort_order'        => 0,
                    'metadata'          => null,
                    'is_gallery_visual' => false,
                    'uploaded_by'       => $uploadedBy,
                    'status'            => true,
                ];

                $media = $this->mediaService->createMedia($mediaData);
                $createdMedia[] = $media;
            }

            return response()->json([
                'message' => 'Files uploaded successfully.',
                'media' => $createdMedia
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Upload failed: ' . $e->getMessage()], 500);
        }
    }

    public function edit($mediaId)
    {
        try {
            $media = Media::findOrFail($mediaId);
            $park = NationalPark::findOrFail($media->mediable_id);

            return view('admin.media.form', compact('media', 'park'));
        } catch (\Exception $e) {
            return back()->withErrors('Failed to load media: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $parkId, $mediaId)
    {
        // dd($request->all());
        try {
            $validated = $request->validate([
                'files'   => 'nullable|array', // not required now
                'files.*' => 'file|mimes:jpeg,png,jpg,gif,webp|max:5120', // validate only if file exists
            ]);

            $media = Media::findOrFail($mediaId);

            if (!empty($validated['files']) && count($validated['files']) > 0) {
                // Delete old file from S3
                $this->uploadService->deleteImage($media->s3_url);

                // Upload new file (only first file considered for update)
                $file = $validated['files'][0];
                $folder = 'national-park/' . $parkId;
                $url = $this->uploadService->uploadImage($file, $folder);

                // Prepare new data
                $updateData = [
                    's3_url'    => $url,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ];

                $this->mediaService->updateMedia($media, $updateData);
            }

            return response()->json(['message' => 'Media updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Update failed: ' . $e->getMessage()], 500);
        }
    }


    public function destroy($parkId, $mediaId)
    {
        try {
            $media = Media::findOrFail($mediaId);

            // Delete image from S3
            $this->uploadService->deleteImage($media->s3_url);

            // Delete record
            $this->mediaService->deleteMedia($media);

            return response()->json(['message' => 'Media deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Delete failed: ' . $e->getMessage()], 500);
        }
    }

    public function toggleGalleryVisual(Request $request)
    {
        $request->validate([
            'media_id' => 'required|integer|exists:media,id',
            'is_gallery_visual' => 'required|boolean',
        ]);

        try {
            $media = Media::findOrFail($request->input('media_id'));
            $media->is_gallery_visual = $request->input('is_gallery_visual');
            $media->save();

            return response()->json(['message' => 'Gallery visual status updated.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Update failed: ' . $e->getMessage()], 500);
        }
    }

   

    public function toggleStatusSwitch(Request $request)
    {
        $request->validate([
            'media_id' => 'required|integer|exists:media,id',
        ]);

        try {
            $media = Media::findOrFail($request->input('media_id'));
            $media->status = !$media->status;
            $media->save();

            return response()->json([
                'message' => 'Status toggled successfully.',
                'new_status' => $media->status,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Status toggle failed: ' . $e->getMessage()], 500);
        }
    }
}
