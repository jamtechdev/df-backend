<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\NationalParkTranslationDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NationalPark;
use App\Models\NationalParkTranslation;
use App\Models\Theme;
use App\Services\NationalParkTranslationService;
use Exception;
use App\Services\UploadService;

class NationalParkTranslationController extends Controller
{
    protected $translationService;
    protected $uploadService;

    public function __construct(NationalParkTranslationService $translationService, UploadService $uploadService)
    {
        $this->translationService = $translationService;
        $this->uploadService = $uploadService;
    }

    /**
     * Display a listing of the national park translations.
     *
     * @return \Illuminate\View\View
     */
    public function index(NationalParkTranslationDataTable $dataTable, $national_park_id = null)
    {
        try {
            $dataTable->setNationalParkId($national_park_id); // 👈 Set it here
            return $dataTable->render('admin.national-park-translation.index', compact('national_park_id'));
        } catch (Exception $e) {
            return back()->withErrors('Failed to load translations page: ' . $e->getMessage());
        }
    }


    /**
     * Handle image upload and save to S3.
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:5120',
            'previous_url' => 'nullable|string', // optional: old file URL
        ]);

        $folder = $request->input('folder', 'hero-section-images');
        $previousUrl = $request->input('previous_url');

        try {
            $url = $this->uploadService->uploadImage($request->file('file'), $folder, $previousUrl);
            return response()->json(['url' => $url], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Upload failed', 'error' => $e->getMessage()], 500);
        }
    }


    /**
     * Delete image from S3.
     */
    public function deleteImage(Request $request)
    {
        $request->validate([
            'url' => 'required|string',
        ]);

        try {
            $this->uploadService->deleteImage($request->input('url'));
            return response()->json(['message' => 'Image deleted successfully.'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Delete failed', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Fetch data for datatable.
     */
    public function fetchData($national_park_id = null)
    {
        // This method is no longer needed for DataTable AJAX, so we can remove or comment it out.
        // Alternatively, we can redirect or return an error if called.
        return response()->json(['message' => 'Use DataTable AJAX endpoint instead.'], 400);
    }

    /**
     * Return DataTable AJAX response.
     */
    // Removed the separate dataTable method as index now handles DataTable rendering

    /**
     * Show the form for creating a new translation.
     */
    public function create($national_park_id = null)
    {
        try {
            $nationalPark = NationalPark::findOrFail($national_park_id); // throws 404 if not found
            $themes = Theme::all(); // Fetch all available themes for dropdown

            return view('admin.national-park-translation.form', compact('nationalPark', 'themes', 'national_park_id'));
        } catch (\Exception $e) {
            return back()->withErrors('Failed to load create form: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created translation in storage.
     */
    public function store(Request $request)
    {

        $data = $request->validate([
            'national_park_id' => 'required|integer',
            'language_code' => 'required|string|max:5',
            'status' => 'required|in:draft,published',
            'theme_id' => 'nullable|integer',
            'lead_quote' => 'nullable|string',
            'title' => 'required|string',
            'subtitle' => 'nullable|string',
            'intro_text_first' => 'nullable|string',
            'park_stats' => 'nullable|array',
            'hero_background' => 'nullable|string',
            'hero_section' => 'nullable|array',
            'conservation_heading' => 'nullable|string',
            'conservation_text' => 'nullable|string',
            'visuals_title' => 'nullable|string',
            'visuals_subtitle' => 'nullable|string',
            'closing_quote' => 'nullable|array',
            'closing_quote.*.title' => 'nullable|string',
            'closing_quote.*.description' => 'nullable|string',
            'meta_one' => 'nullable|string',
            'published_at' => 'nullable|date',
        ]);

        $data['slug'] = \Illuminate\Support\Str::slug($data['title']);

        if (!empty($data['hero_background'])) {
            $heroBackground = json_decode($data['hero_background'], true);
            $heroTitle = $data['hero_section']['title'] ?? null;

            $data['hero_image_content'] = [
                'background' => $heroBackground['url'] ?? null,
                'title' => $heroTitle
            ];
        } else {
            unset($data['hero_image_content']);
        }

        unset($data['hero_background'], $data['hero_section']);

        // ✅ Handle closing_quote: convert to JSON if present
        $data['closing_quote'] = !empty($data['closing_quote']) ? json_encode($data['closing_quote']) : null;

        try {
            $this->translationService->createTranslation($data);

            return $request->ajax()
                ? response()->json(['message' => 'Translation created successfully.'], 200)
                : redirect()->route('national-parks.translation.index')->with('success', 'Translation created successfully.');
        } catch (Exception $e) {
            return $request->ajax()
                ? response()->json(['message' => 'Failed to create translation: ' . $e->getMessage()], 500)
                : back()->withErrors('Failed to create translation: ' . $e->getMessage());
        }
    }




    /**
     * Show the form for editing the specified translation.
     */
    public function edit($id)
    {
        try {
            $translation = NationalParkTranslation::find($id);
            
            $nationalPark = NationalPark::find($translation->national_park_id);
            $translation->closing_quote = $translation->closing_quote;
            $themes = Theme::all(); // Fetch all available themes for dropdown

            // dd($translation);
            return view('admin.national-park-translation.form', compact('translation', 'nationalPark', 'themes'));
        } catch (Exception $e) {
            return back()->withErrors('Failed to load edit form: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified translation in storage.
     */
   public function update(Request $request, $id)
{
    $data = $request->validate([
        'language_code' => 'required|string|max:5',
        'status' => 'required|in:draft,published',
        'theme_id' => 'nullable|integer',
        'lead_quote' => 'nullable|string',
        'title' => 'required|string',
        'subtitle' => 'nullable|string',
        'intro_text_first' => 'nullable|string',
        'park_stats' => 'nullable|array',
        'hero_background' => 'nullable|string',
        'hero_section' => 'nullable|array',
        'conservation_heading' => 'nullable|string',
        'conservation_text' => 'nullable|string',
        'visuals_title' => 'nullable|string',
        'visuals_subtitle' => 'nullable|string',
        'closing_quote' => 'nullable|array',
        'closing_quote.*.title' => 'nullable|string',
        'closing_quote.*.description' => 'nullable|string',
        'meta_one' => 'nullable|string',
        'published_at' => 'nullable|date',
    ]);

    $translation = NationalParkTranslation::findOrFail($id);
    $data['national_park_id'] = $translation->national_park_id;

    // Generate unique slug
    $newSlug = \Illuminate\Support\Str::slug($data['title']);
    $slugExists = NationalParkTranslation::where('slug', $newSlug)
        ->where('id', '!=', $id)
        ->exists();

    if ($slugExists) {
        $newSlug .= '-' . $id;
    }
    $data['slug'] = $newSlug;

    // Handle hero_image_content automatically as array
    if (!empty($data['hero_background'])) {
        $heroBackground = json_decode($data['hero_background'], true);
        $heroTitle = $data['hero_section']['title'] ?? null;

        $data['hero_image_content'] = [
            'background' => $heroBackground['url'] ?? null,
            'title' => $heroTitle
        ];
    } else {
        $data['hero_image_content'] = null;
    }

    unset($data['hero_background'], $data['hero_section']); // Clean up these temp fields

    try {
        $this->translationService->updateTranslation($id, $data);

        return $request->ajax()
            ? response()->json(['message' => 'Translation updated successfully.'], 200)
            : redirect()->route('national-parks.translation.index')->with('success', 'Translation updated successfully.');
    } catch (\Exception $e) {
        return $request->ajax()
            ? response()->json(['message' => 'Failed to update translation: ' . $e->getMessage()], 500)
            : back()->withErrors('Failed to update translation: ' . $e->getMessage());
    }
}




    /**
     * Remove the specified translation from storage.
     */
    public function destroy($id)
    {
        try {
            $this->translationService->deleteTranslation($id);
            return response()->json(['message' => 'Translation deleted successfully.']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to delete translation.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get translations by national park ID as JSON.
     */
    public function getByNationalParkId($national_park_id)
    {
        try {
            $translations = $this->translationService->getByNationalParkId($national_park_id);
            return response()->json(['translations' => $translations]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to get translations.', 'error' => $e->getMessage()], 500);
        }
    }
}
