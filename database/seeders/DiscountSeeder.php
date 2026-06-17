<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        Discount::create([
            'code' => 'EARLYBIRDS25',
            'type' => 'ebook',
            'percentage' => 25.00,
            'is_active' => true,
        ]);
    }
}
