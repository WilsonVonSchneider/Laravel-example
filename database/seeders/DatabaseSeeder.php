<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Admin user is created by seeding the database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'country' => 'us',
            'category' => 'general',
            'role' => true,
            'language' => 'en',
            'password' => Hash::make('Admin666!')
        ]);

        User::factory(10)->create();
    }
}
