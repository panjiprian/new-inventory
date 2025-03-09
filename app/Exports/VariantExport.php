<?php

namespace App\Exports;

use App\Models\Variant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VariantExport implements FromCollection, WithHeadings
{
    /**
     * Mengambil data varian beserta nama kategori & user (pembuat dan pengupdate)
     */
    public function collection()
    {
        return Variant::with(['category', 'creator', 'updater']) // Ambil relasi kategori & user
            ->get()
            ->map(function ($variant) {
                return [
                    'category_name' => $variant->category ? $variant->category->name : 'No Category',
                    'code'          => $variant->code,
                    'name'          => $variant->name,
                    'created_by'    => $variant->creator ? $variant->creator->name : '-',
                    'updated_by'    => $variant->updater ? $variant->updater->name : '-',
                ];
            });
    }

    /**
     * Menambahkan header pada file Excel
     */
    public function headings(): array
    {
        return [
            'Category Name', // Nama kategori, bukan ID
            'Code',
            'Variant Name',
            'Created By',  // Nama pembuat, bukan ID
            'Updated By',  // Nama pengupdate, bukan ID
        ];
    }
}
