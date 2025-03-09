<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExport implements FromCollection, WithHeadings
{
    /**
     * Mengambil data produk beserta nama kategori, varian, dan user
     */
    public function collection()
    {
        return Product::with(['category', 'variant', 'createdBy', 'updatedBy']) // Ambil relasi user
            ->get()
            ->map(function ($product) {
                return [
                    'code'        => $product->code,
                    'name'        => $product->name,
                    'category'    => $product->category ? $product->category->name : 'No Category',
                    'variant'     => $product->variant ? $product->variant->name : 'No Variant',
                    'stock'       => $product->stock,
                    'price'       => number_format($product->price, 2, '.', ','), // Format harga dengan 2 desimal
                    'description' => $product->description,
                    'created_by'  => $product->createdBy ? $product->createdBy->name : '-',
                    'updated_by'  => $product->updatedBy ? $product->updatedBy->name : '-',
                ];
            });
    }

    /**
     * Menambahkan header pada file Excel
     */
    public function headings(): array
    {
        return [
            'Code',
            'Product Name',
            'Category',
            'Variant',
            'Stock',
            'Price',
            'Description',
            'Created By',
            'Updated By',
        ];
    }
}
