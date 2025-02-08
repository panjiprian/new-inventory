<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id(); // Primary Key
        $table->string('code', 20)->unique(); // Kode unik produk
        $table->string('name'); // Nama produk
        $table->text('description')->nullable(); // Deskripsi produk
        $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // Relasi ke kategori
        $table->foreignId('variant_id')->constrained('variants')->onDelete('cascade'); // Relasi ke varian
        $table->integer('stock')->default(0); // Jumlah stok
        $table->decimal('price', 10, 2); // Harga produk
        $table->string('image')->nullable(); // Gambar produk
        $table->foreignId('created_by')->constrained('users'); // User yang membuat produk
        $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete(); // User yang terakhir mengupdate
        $table->timestamps(); // created_at & updated_at
    });
}


    /**
     * Rollback migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
