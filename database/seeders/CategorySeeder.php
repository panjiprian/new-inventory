<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            'name' => "Cosmetics",
        ]);

        DB::table('categories')->insert([
            'name' => "Bag Brand/Non Brand",
        ]);

        DB::table('categories')->insert([
            'name' => "Shoes",
        ]);

        DB::table('categories')->insert([
            'name' => "Garment",
        ]);
    }
}
