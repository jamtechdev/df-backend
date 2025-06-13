<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Theme;

class ThemesTableSeeder extends Seeder
{
    public function run(): void
    {
        Theme::insert([
            [
                'name' => 'Light Theme',
                'root' => json_encode(['background' => '#fff', 'color' => '#000']),
                'slug' => 'light-theme',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dark Theme',
                'root' => json_encode(['background' => '#000', 'color' => '#fff']),
                'slug' => 'dark-theme',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
         $this->command->info('Themes created successfully!');
    }
}
