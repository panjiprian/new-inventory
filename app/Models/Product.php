<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    // Tentukan tabel yang digunakan
    protected $table = 'products';

    // Kolom yang bisa diisi secara massal
    protected $fillable = [
        'category_id',
        'variant_id',
        'code',
        'name',
        'description',
        'stock',
        'price',
        'image',
        'created_by',
        'updated_by'
    ];

    /**
     * Relasi ke kategori (satu produk memiliki satu kategori)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke varian (satu produk memiliki satu varian)
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    /**
     * Relasi ke user yang membuat produk
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke user yang mengupdate produk terakhir kali
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
