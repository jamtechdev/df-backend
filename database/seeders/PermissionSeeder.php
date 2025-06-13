<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PermissionSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {




        // Define all permissions
        $permissions = [
            // Themes
            'theme.view',
            'theme.create',
            'theme.update',
            'theme.delete',

            // Projects
            'project.view',
            'project.create',
            'project.update',
            'project.delete',

            // Menus
            'menu.view',
            'menu.create',
            'menu.update',
            'menu.delete',

            // Project Users
            'project_user.view',
            'project_user.assign',
            'project_user.update',
            'project_user.remove',

            // Categories
            'category.view',
            'category.create',
            'category.update',
            'category.delete',

            // Category Translations
            'category_translation.view',
            'category_translation.create',
            'category_translation.update',
            'category_translation.delete',

            // National Parks
            'national_park.view',
            'national_park.create',
            'national_park.update',
            'national_park.delete',

            // National Park Translations
            'national_park_translation.view',
            'national_park_translation.create',
            'national_park_translation.update',
            'national_park_translation.delete',
            'national_park_translation.publish',

            // Media
            'media.view',
            'media.upload',
            'media.update',
            'media.delete',

            // Media Translations
            'media_translation.view',
            'media_translation.create',
            'media_translation.update',
            'media_translation.delete',

            // Hidden Wonders
            'hidden_wonder.view',
            'hidden_wonder.create',
            'hidden_wonder.update',
            'hidden_wonder.delete',

            // Content Blocks
            'content_block.view',
            'content_block.create',
            'content_block.update',
            'content_block.delete',

            // Content Analytics & Feedback
            'content_analytics.view',
            'content_feedback.view',

            // Project Testimonials
            'project_testimonial.view',
            'project_testimonial.create',
            'project_testimonial.update',
            'project_testimonial.delete',
        ];


        // Create all permissions
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }


        // Create roles

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $contentManagerRole = Role::firstOrCreate(['name' => 'content_manager']);
        $readerRole = Role::firstOrCreate(['name' => 'reader']);


        // Assign all permissions to admin

        $adminRole->syncPermissions(Permission::all());


        // Assign limited permissions to manager
        $managerPermissions = [
            'project.view',
            'project.create',
            'project.update',
            'project_user.view',
            'project_user.assign',
            'category.view',
            'category.create',
            'category.update',
            'content_analytics.view',
            'content_feedback.view',
        ];
        $managerRole->syncPermissions($managerPermissions);



        // Assign limited permissions to content manager
        $contentManagerPermissions = [
            'theme.view',
            'theme.update',
            'menu.view',
            'menu.update',
            'media.view',
            'media.upload',
            'media.update',
            'hidden_wonder.view',
            'hidden_wonder.create',
            'hidden_wonder.update',
            'content_block.view',
            'content_block.update',
            'project_testimonial.view',
            'project_testimonial.create',
            'project_testimonial.update',
        ];
        $contentManagerRole->syncPermissions($contentManagerPermissions);


        // Assign read-only permissions to reader
        $readerPermissions = [
            'theme.view',
            'project.view',
            'menu.view',
            'project_user.view',
            'category.view',
            'category_translation.view',
            'national_park.view',
            'national_park_translation.view',
            'media.view',
            'media_translation.view',
            'hidden_wonder.view',
            'content_block.view',
            'content_analytics.view',
            'content_feedback.view',
            'project_testimonial.view',
        ];
        $readerRole->syncPermissions($readerPermissions);



        // Create 1 Admin user

        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => Hash::make('password@123'),
                'address' => 'Admin Address',
                'phone' => '0000000000'
            ]
        );
        $admin->assignRole($adminRole);


        // Create 5 Manager users
        for ($i = 1; $i <= 2; $i++) {
            $manager = User::firstOrCreate(
                ['email' => "manager{$i}@gmail.com"],
                [
                    'first_name' => 'Manager',
                    'last_name' => "User{$i}",
                    'password' => Hash::make('password@123'),
                    'address' => "Manager Address {$i}",
                    'phone' => "100000000{$i}"
                ]
            );
            $manager->assignRole($managerRole);
        }

        // Create 5 Content Manager users
        for ($i = 1; $i <= 2; $i++) {
            $contentManager = User::firstOrCreate(
                ['email' => "contentmanager{$i}@gmail.com"],
                [
                    'first_name' => 'ContentManager',
                    'last_name' => "User{$i}",
                    'password' => Hash::make('password@123'),
                    'address' => "Content Manager Address {$i}",
                    'phone' => "200000000{$i}"
                ]
            );
            $contentManager->assignRole($contentManagerRole);
        }

        // Create 5 Reader users
        for ($i = 1; $i <= 2; $i++) {
            $reader = User::firstOrCreate(
                ['email' => "reader{$i}@gmail.com"],
                [
                    'first_name' => 'Reader',
                    'last_name' => "User{$i}",
                    'password' => Hash::make('password@123'),
                    'address' => "Reader Address {$i}",
                    'phone' => "300000000{$i}"
                ]
            );
            $reader->assignRole($readerRole);
        }

        $this->command->info('Permissions created successfully!');
    }
}
