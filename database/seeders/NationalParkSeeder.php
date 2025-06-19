<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NationalPark;
use App\Models\NationalParkTranslation;
use Carbon\Carbon;

class NationalParkSeeder extends Seeder
{
    public function run(): void
    {
        // Example National Parks data
        $parks = [
            [
                'category_id' => 1,
                'name' => 'Yellowstone National Park',
                'slug' => 'yellowstone-national-park',
                'theme_id' => 1,
                'seo_title' => 'Yellowstone National Park',
                'seo_description' => 'Explore the beauty of Yellowstone.',
                'seo_keywords' => 'Yellowstone, National Park, Wildlife',
                'is_featured' => true,
                'translations' => [
                    [
                        'language_code' => 'en',
                        'status' => 'published',
                        'theme_id' => 1,
                        'lead_quote' => 'The world\'s first national park.',
                        'title' => 'Yellowstone National Park',
                        'subtitle' => 'A true natural wonder',
                        'intro_text_first' => '<p>Yellowstone is famous for its geothermal features.</p>',
                        'park_stats' => [
                            [
                                'icon' => 'tree',
                                'value' => '2,219,789 acres',
                                'label' => 'Area',
                                'description' => 'Park area'
                            ],
                            [
                                'icon' => 'users',
                                'value' => '4 million',
                                'label' => 'Visitors',
                                'description' => 'Annual visitors'
                            ]
                        ],
                        'hero_image_content' => [
                            'background' => 'yellowstone.jpg',
                            'title' => 'Old Faithful Geyser'
                        ],
                        'conservation_heading' => 'Conservation at Yellowstone',
                        'conservation_text' => 'Preserving nature for future generations.',
                        'visuals_title' => 'Natural Beauty',
                        'visuals_subtitle' => 'Breathtaking views',
                        'slug' => 'yellowstone-national-park-en',
                        'closing_quote' => [
                            [
                                'title' => 'Nature at its best',
                                'description' => 'Experience untouched wilderness.'
                            ]
                        ],
                        'meta_one' => 'Meta information EN',
                        'published_at' => Carbon::now(),
                    ],
                    [
                        'language_code' => 'fr',
                        'status' => 'draft',
                        'theme_id' => 1,
                        'lead_quote' => 'Le premier parc national du monde.',
                        'title' => 'Parc National de Yellowstone',
                        'subtitle' => 'Une merveille naturelle',
                        'intro_text_first' => '<p>Yellowstone est célèbre pour ses caractéristiques géothermiques.</p>',
                        'park_stats' => [
                            [
                                'icon' => 'arbre',
                                'value' => '2 219 789 acres',
                                'label' => 'Superficie',
                                'description' => 'Zone du parc'
                            ],
                            [
                                'icon' => 'utilisateurs',
                                'value' => '4 millions',
                                'label' => 'Visiteurs',
                                'description' => 'Visiteurs annuels'
                            ]
                        ],
                        'hero_image_content' => [
                            'background' => 'yellowstone_fr.jpg',
                            'title' => 'Old Faithful Geyser FR'
                        ],
                        'conservation_heading' => 'Conservation à Yellowstone',
                        'conservation_text' => 'Préserver la nature pour les générations futures.',
                        'visuals_title' => 'Beauté naturelle',
                        'visuals_subtitle' => 'Vues à couper le souffle',
                        'slug' => 'parc-national-de-yellowstone-fr',
                        'closing_quote' => [
                            [
                                'title' => 'La nature à son meilleur',
                                'description' => 'Découvrez la nature sauvage.'
                            ]
                        ],
                        'meta_one' => 'Information méta FR',
                        'published_at' => null,
                    ],
                ]
            ]
        ];

        // Insert data into national_parks and national_park_translations
        foreach ($parks as $parkData) {
            $translations = $parkData['translations'];
            unset($parkData['translations']);

            $park = NationalPark::create($parkData);

            foreach ($translations as $translation) {
                $translation['national_park_id'] = $park->id;

                // Convert JSON casted fields
                $translation['park_stats'] = $translation['park_stats'];
                $translation['hero_image_content'] = $translation['hero_image_content'];
                $translation['closing_quote'] = $translation['closing_quote'];

                NationalParkTranslation::create($translation);
            }
        }
    }
}
