<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('koperasi_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained();
            $table->string('payment_gateway_reference', 100)->unique(); // Dari Midtrans/Xendit
            $table->unsignedBigInteger('amount');
            $table->string('status', 20)->default('pending'); // pending | settled | expired | failed
            $table->text('qr_url')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('payment_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('koperasi_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_id')->constrained();
            $table->string('provider_event_id', 100)->unique(); // Kunci Idempotensi
            $table->json('payload');
            $table->timestamp('created_at')->useCurrent(); // Append-Only
        });

        Schema::create('complaints', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('koperasi_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('order_id')->nullable()->constrained();
            $table->string('subject', 150);
            $table->text('description');
            $table->string('attachment_path')->nullable();
            $table->string('status', 20)->default('open');
            $table->text('admin_reply')->nullable();
            $table->timestamps();
        });

        Schema::create('report_snapshots', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('koperasi_id')->constrained()->cascadeOnDelete();
            $table->date('report_date');
            $table->unsignedBigInteger('total_sales');
            $table->integer('total_transactions');
            $table->integer('total_products_sold');
            $table->integer('total_complaints');
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('announcements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('target_koperasi_id')->nullable()->constrained('koperasis')->cascadeOnDelete(); // Null = Semua
            $table->string('title', 150);
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('report_snapshots');
        Schema::dropIfExists('complaints');
        Schema::dropIfExists('payment_events');
        Schema::dropIfExists('payments');
    }
};