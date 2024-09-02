<?php

namespace Database\Seeders;

use App\Models\Entity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Entity::insert(
            [
                ['name' => 'Santiago Torres', 'Nit' => '123456789'],
                ['name' => 'Andrés Rincón', 'Nit' => '23456789'],
            ]
        );
    }
}
