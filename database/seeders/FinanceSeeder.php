<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FinanceSeeder extends Seeder
{
    public function run(): void
    {
        // Create Finance Admin
        User::updateOrCreate(
            ['email' => 'financeadmin@nathanielgyarteng.com'],
            [
                'name' => 'Finance Admin',
                'email' => 'financeadmin@nathanielgyarteng.com',
                'password' => Hash::make('finance123'),
                'is_admin' => false,
                'is_staff' => true,
                'role' => 'Finance Admin',
                'email_verified_at' => now(),
            ]
        );

        // Create Finance Member
        User::updateOrCreate(
            ['email' => 'financemember@nathanielgyarteng.com'],
            [
                'name' => 'Finance Member',
                'email' => 'financemember@nathanielgyarteng.com',
                'password' => Hash::make('finance123'),
                'is_admin' => false,
                'is_staff' => true,
                'role' => 'Finance Member',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Finance team users created successfully!');
        $this->command->info('Finance Admin - Email: financeadmin@nathanielgyarteng.com, Password: finance123');
        $this->command->info('Finance Member - Email: financemember@nathanielgyarteng.com, Password: finance123');
    }
}