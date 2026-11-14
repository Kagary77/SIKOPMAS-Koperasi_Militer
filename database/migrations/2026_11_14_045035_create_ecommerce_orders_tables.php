<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('koperasi_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['customer_id', 'koperasi_id']); // 1 Cart per Koperasi
        });

        Schema::create('cart_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('koperasi_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('product_id');
            $table->integer('qty');
            $table->timestamps();

            $table->foreign(['koperasi_id', 'product_id'])
                ->references(['koperasi_id', 'id'])->on('products')->restrictOnDelete();
        });

        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('koperasi_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('order_number', 50)->unique();
            $table->unsignedBigInteger('total_amount');
            $table->string('status', 30)->default('pending_payment');
            $table->timestamp('payment_deadline')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->unique(['koperasi_id', 'id']);
        });

        Schema::create('order_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('koperasi_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('product_id');
            $table->string('product_name_snapshot', 150);
            $table->unsignedBigInteger('price_snapshot');
            $table->integer('qty');
            $table->unsignedBigInteger('subtotal');
            $table->timestamps();

            $table->foreign(['koperasi_id', 'product_id'])
                ->references(['koperasi_id', 'id'])->on('products')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
    }
};