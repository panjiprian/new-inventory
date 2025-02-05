<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('suppliers')->insert([
            'name' => "Guangdong Renhe Guozhuang Biotechnology Co., Ltd.",
            'address' => "Guangzhou",
            'phone'=>'08126677522',
            'email'=>'Guangdong@gmailcom'
        ]);
    }
}
