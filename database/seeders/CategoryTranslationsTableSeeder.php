<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryTranslationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoriesData = [
            [
                'language_code' => 'en',
                'name' => 'National Parks',
                'description' => 'Explore the beauty of nature.',
                'icon' => 'leaf',
                'is_active' => true,
            ],
            [
                'language_code' => 'en',
                'name' => 'Marine Sanctuaries',
                'description' => 'Discover historical places and stories.',
                'icon' => 'book',
                'is_active' => true,
            ],
            [
                'language_code' => 'en',
                'name' => 'Wild Life',
                'description' => 'Experience thrilling adventures.',
                'icon' => 'mountain',
                'is_active' => true,
            ],
        ];

        foreach ($categoriesData as $data) {
            // Insert into categories table
            $categoryId = DB::table('categories')->insertGetId([
                'slug' => Str::slug($data['name']),
                'icon' => $data['icon'],
                'is_active' => $data['is_active'],
            ]);

            // Insert into category_translations table
            DB::table('category_translations')->insert([
                'category_id' => $categoryId,
                'language_code' => $data['language_code'],
                'name' => $data['name'],
                'description' => $data['description'],
            ]);
        }
    }
}
