<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cashier_shifts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('koperasi_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users'); // Kasir
            $table->timestamp('start_time')->useCurrent();
            $table->timestamp('end_time')->nullable();
            $table->unsignedBigInteger('starting_cash');
            $table->unsignedBigInteger('expected_cash')->nullable();
            $table->unsignedBigInteger('actual_cash')->nullable();
            $table->string('status', 20)->default('open'); // open | closed
            $table->timestamps();
        });

        Schema::create('sales', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('koperasi_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shift_id')->constrained('cashier_shifts');
            $table->string('invoice_number', 50)->unique();
            $table->unsignedBigInteger('total_amount');
            $table->string('payment_method', 20); // cash | qris
            $table->unsignedBigInteger('amount_paid');
            $table->unsignedBigInteger('change_amount');
            $table->string('status', 20)->default('completed');
            $table->timestamps();

            $table->unique(['koperasi_id', 'id']);
        });

        Schema::create('sale_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('koperasi_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('product_id');
            $table->string('product_name_snapshot', 150); // Snapshot histori
            $table->unsignedBigInteger('price_snapshot');
            $table->integer('qty');
            $table->unsignedBigInteger('subtotal');
            $table->timestamps();

            // Composite FK
            $table->foreign(['koperasi_id', 'product_id'])
                ->references(['koperasi_id', 'id'])->on('products')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
        Schema::dropIfExists('cashier_shifts');
    }
};