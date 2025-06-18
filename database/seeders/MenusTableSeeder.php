<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenusTableSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            // Only "National Parks" menu for Project ID 2
            [
                'project_id' => 2,
                'parent_id' => 0,
                'order' => 1,
                'title' => 'National Parks',
                'icon' => 'fas fa-tree',
                'uri' => '/national-parks',
                'permission' => json_encode(['admin']), // Only Admin can see
            ]
        ];

        Menu::insert($menus);

        $this->command->info('Only "National Parks" menu created for Project ID 2!');
    }
}
