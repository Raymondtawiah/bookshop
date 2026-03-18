<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing admin if exists
        User::where('email', 'admin@nathanielgyarteng.com')->delete();
        
        // Create fresh admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@nathanielgyarteng.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
            'email_verified_at' => now(), // Admin is auto-verified
        ]);
        
        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@nathanielgyarteng.com');
        $this->command->info('Password: admin123');
    }
}
