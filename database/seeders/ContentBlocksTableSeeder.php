<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContentBlock;

class ContentBlocksTableSeeder extends Seeder
{
    public function run()
    {
        ContentBlock::truncate();

        ContentBlock::create([
            'national_park_translation_id' => 1,
            'section_type' => 'key_feature',
            'heading' => 'Majestic Mountains',
            'subheading' => 'Explore the peaks',
            'icon' => 'mountain-icon',
            'title' => 'Mountain Adventure',
            'description' => 'Experience breathtaking views and challenging hikes.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        ContentBlock::create([
            'national_park_translation_id' => 1,
            'section_type' => 'explore',
            'heading' => 'Lush Forests',
            'subheading' => 'Discover nature',
            'icon' => 'forest-icon',
            'title' => 'Forest Trails',
            'description' => 'Walk through serene and beautiful forest trails.',
            'sort_order' => 2,
            'is_active' => true,
        ]);
    }
}
