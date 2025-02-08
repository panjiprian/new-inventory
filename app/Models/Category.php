<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    // Tentukan tabel yang digunakan
    protected $table = 'categories';

    // Kolom yang bisa diisi secara massal
    protected $fillable = [
        'code', // Kode kategori (digunakan untuk kode unik produk)
        'name',
        'created_by',
        'updated_by'
    ];

    /**
     * Relasi ke varian (satu kategori bisa memiliki banyak varian)
     */
    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }

    /**
     * Relasi ke produk (satu kategori bisa memiliki banyak produk)
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
