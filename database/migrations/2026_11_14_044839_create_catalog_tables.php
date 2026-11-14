<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('koperasi_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->timestamps();
            
            // Target Composite FK
            $table->unique(['koperasi_id', 'id']);
        });

        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('koperasi_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('category_id');
            $table->string('name', 150);
            $table->string('sku', 50)->nullable();
            $table->string('barcode', 50)->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('price'); // BigInt untuk Rupiah
            $table->integer('stock')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['koperasi_id', 'id']);
            $table->index(['koperasi_id', 'barcode']);
            $table->index(['koperasi_id', 'is_active']);

            // Composite FK: Kategori harus milik koperasi yang sama
            $table->foreign(['koperasi_id', 'category_id'])
                ->references(['koperasi_id', 'id'])->on('categories')->restrictOnDelete();
        });

        Schema::create('stock_movements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('koperasi_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('product_id');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type', 30); // in, out, adjustment, sale_pos, sale_online
            $table->integer('qty'); // Positif = masuk, Negatif = keluar
            $table->integer('stock_before');
            $table->integer('stock_after');
            $table->string('reference_id')->nullable(); // ID Sale/Order terkait
            $table->string('notes')->nullable();
            $table->timestamp('created_at')->useCurrent(); // Append-Only

            $table->index(['koperasi_id', 'product_id', 'created_at']);

            // Composite FK: Mutasi stok harus merujuk produk di koperasi yang sama
            $table->foreign(['koperasi_id', 'product_id'])
                ->references(['koperasi_id', 'id'])->on('products')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};