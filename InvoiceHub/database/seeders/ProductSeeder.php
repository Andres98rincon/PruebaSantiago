<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::insert([
            ['name' => 'Carne de res', 'price' => 200],
            ['name' => 'Carne de cerdo', 'price' => 300],
            ['name' => 'pollo', 'price' => 400],
        ]);
    }
}
