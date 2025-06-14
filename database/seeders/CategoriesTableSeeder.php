<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'slug' => 'nature',
                'icon' => 'leaf',
                'is_active' => true,
            ],
            [
                'slug' => 'history',
                'icon' => 'book',
                'is_active' => true,
            ],
            [
                'slug' => 'adventure',
                'icon' => 'mountain',
                'is_active' => true,
            ],
        ]);
    }
}
