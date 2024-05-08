<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Check if Admin with email already exists
        if (!Admin::where('email', 'admin@example.com')->exists()) {
            // Seed admins
            Admin::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'), 
                'role' => 'admin',
            ]);

            // Add more admins if needed
        }
    }
}
