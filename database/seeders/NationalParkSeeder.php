<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NationalPark;
use App\Models\NationalParkTranslation;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NationalParkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data for National Parks
        $parks = [
            [
                'category_id' => 1,
                'name' => 'Yellowstone National Park',
                'slug' => 'yellowstone-national-park',
                'theme_id' => 1,
                'seo_title' => 'Yellowstone National Park',
                'seo_description' => 'Explore the wonders of Yellowstone National Park.',
                'seo_keywords' => 'Yellowstone, National Park, Nature',
                'is_featured' => true,
                'translations' => [
                    [
                        'language_code' => 'en',
                        'status' => 'published',
                        'theme_id' => 1,
                        'lead_quote' => 'The first national park in the world.',
                        'title' => 'Yellowstone National Park',
                        'subtitle' => 'A natural wonder',
                        'intro_text_first' => 'Yellowstone is known for its wildlife and geothermal features.',
                        'park_stats' => json_encode(['area' => '2,219,789 acres', 'visitors' => 4000000]),
                        'hero_image_content' => json_encode(['image_url' => 'yellowstone.jpg', 'caption' => 'Old Faithful']),
                        'conservation_heading' => 'Conservation Efforts',
                        'conservation_text' => 'Efforts to preserve the park\'s natural beauty.',
                        'visuals_title' => 'Visual Highlights',
                        'visuals_subtitle' => 'Stunning landscapes',
                        'slug' => 'yellowstone-national-park-en',
                        'closing_quote' => json_encode(['quote' => 'Nature at its best']),
                        'meta_one' => 'Meta information',
                      
                        'published_at' => Carbon::now(),
                    ],
                    [
                        'language_code' => 'fr',
                        'status' => 'draft',
                        'theme_id' => 1,
                        'lead_quote' => 'Le premier parc national au monde.',
                        'title' => 'Parc National de Yellowstone',
                        'subtitle' => 'Une merveille naturelle',
                        'intro_text_first' => 'Yellowstone est connu pour sa faune et ses caractéristiques géothermiques.',
                        'park_stats' => json_encode(['surface' => '2 219 789 acres', 'visiteurs' => 4000000]),
                        'hero_image_content' => json_encode(['image_url' => 'yellowstone_fr.jpg', 'caption' => 'Old Faithful']),
                        'conservation_heading' => 'Efforts de conservation',
                        'conservation_text' => 'Efforts pour préserver la beauté naturelle du parc.',
                        'visuals_title' => 'Points forts visuels',
                        'visuals_subtitle' => 'Paysages à couper le souffle',
                        'slug' => 'parc-national-de-yellowstone-fr',
                        'closing_quote' => json_encode(['quote' => 'La nature à son meilleur']),
                        'meta_one' => 'Informations méta',
                      
                        'published_at' => null,
                    ],
                ],
            ],
            // Add more parks if needed
        ];

        foreach ($parks as $parkData) {
            $translations = $parkData['translations'];
            unset($parkData['translations']);

            $park = NationalPark::create($parkData);

            foreach ($translations as $translationData) {
                $translationData['national_park_id'] = $park->id;
                NationalParkTranslation::create($translationData);
            }
        }
    }
}
