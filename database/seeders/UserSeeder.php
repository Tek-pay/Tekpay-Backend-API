<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Check if user with email already exists
        if (!User::where('email', 'user@example.com')->exists()) {
            // Seed user
            User::create([
                'name' => 'Regular User',
                'email' => 'user@example.com',
                'password' => bcrypt('password'), 
                'role' => 'user', // Set user role
            ]);
        }
    }
}
