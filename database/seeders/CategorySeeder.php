<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['code' => 'ELC', 'name' => 'Electronics'],
            ['code' => 'FUR', 'name' => 'Furniture'],
            ['code' => 'CLT', 'name' => 'Clothing'],
            ['code' => 'FOO', 'name' => 'Food & Beverage'],
            ['code' => 'AUT', 'name' => 'Automotive'],
        ];

        DB::table('categories')->insert($categories);
    }
}
