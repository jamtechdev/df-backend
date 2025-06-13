<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectsTableSeeder extends Seeder
{
    public function run(): void
    {
        Project::insert([
            [
                'name' => 'Delightful Ocean',
                'slug' => 'delightful-ocean',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Delightful America',
                'slug' => 'creative-america',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
         $this->command->info('Project created successfully!');
    }
}
