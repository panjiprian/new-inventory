<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID kategori berdasarkan kode kategori
        $categories = DB::table('categories')->pluck('id', 'code');

        $variants = [
            ['code' => 'LAP', 'name' => 'Laptop', 'category_id' => $categories['ELC']],
            ['code' => 'PHN', 'name' => 'Smartphone', 'category_id' => $categories['ELC']],
            ['code' => 'TBL', 'name' => 'Tablet', 'category_id' => $categories['ELC']],
            ['code' => 'SOF', 'name' => 'Sofa', 'category_id' => $categories['FUR']],
            ['code' => 'CHA', 'name' => 'Chair', 'category_id' => $categories['FUR']],
        ];

        DB::table('variants')->insert($variants);
    }
}
