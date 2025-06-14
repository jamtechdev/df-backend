<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTranslationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assuming the category IDs are 1, 2, 3 as inserted in CategoriesTableSeeder
        DB::table('category_translations')->insert([
            [
                'category_id' => 1,
                'language_code' => 'en',
                'name' => 'Nature',
                'description' => 'Explore the beauty of nature.',
            ],
            [
                'category_id' => 2,
                'language_code' => 'en',
                'name' => 'History',
                'description' => 'Discover historical places and stories.',
            ],
            [
                'category_id' => 3,
                'language_code' => 'en',
                'name' => 'Adventure',
                'description' => 'Experience thrilling adventures.',
            ],
            [
                'category_id' => 1,
                'language_code' => 'es',
                'name' => 'Naturaleza',
                'description' => 'Explora la belleza de la naturaleza.',
            ],
            [
                'category_id' => 2,
                'language_code' => 'es',
                'name' => 'Historia',
                'description' => 'Descubre lugares e historias históricas.',
            ],
            [
                'category_id' => 3,
                'language_code' => 'es',
                'name' => 'Aventura',
                'description' => 'Experimenta aventuras emocionantes.',
            ],
        ]);
    }
}
