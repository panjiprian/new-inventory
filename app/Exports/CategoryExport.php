<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoryExport implements FromCollection, WithHeadings
{
    /**
     * Mengambil data kategori beserta nama user (pembuat dan pengupdate)
     */
    public function collection()
    {
        return Category::with(['creator', 'updater']) // Ambil relasi user
            ->get()
            ->map(function ($category) {
                return [
                    'code'        => $category->code,
                    'name'        => $category->name,
                    'created_by'  => $category->creator ? $category->creator->name : '-',
                    'updated_by'  => $category->updater ? $category->updater->name : '-',
                ];
            });
    }

    /**
     * Menambahkan header pada file Excel
     */
    public function headings(): array
    {
        return [
            'Category Code',
            'Category Name',
            'Created By',  // Nama pembuat, bukan ID
            'Updated By',  // Nama pengupdate, bukan ID
        ];
    }
}
