<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Koperasi (Tenant)
        Schema::create('koperasis', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 100)->unique(); // Untuk URL: /tenant/{koperasi}
            $table->string('code', 50)->unique(); // Kode unik koperasi
            $table->string('status', 20)->default('active'); // active | inactive
            $table->string('address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('bank_name', 50)->nullable();
            $table->string('bank_account_number', 50)->nullable();
            $table->timestamps();
        });

        // 2. Modifikasi Tabel Users (Tambahkan relasi ke Koperasi)
        Schema::table('users', function (Blueprint $table): void {
            // Nullable: Pusat tidak punya koperasi_id
            $table->foreignId('koperasi_id')->nullable()->after('id')->constrained('koperasis')->nullOnDelete();
            // UNIQUE (koperasi_id, id) untuk Composite FK nanti
            $table->unique(['koperasi_id', 'id']);
        });

        // 3. Tabel Customers (Pelanggan B2C)
        Schema::create('customers', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 150);
            $table->string('email')->unique();
            $table->string('phone', 20)->unique()->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // 4. Tabel Audit Logs (Append-Only)
        Schema::create('audit_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 100);
            $table->string('entity_type', 100);
            $table->unsignedBigInteger('entity_id');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();
            // Tidak ada updated_at (Append-Only)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('customers');
        
        Schema::table('users', function (Blueprint $table): void {
            $table->dropUnique(['koperasi_id', 'id']);
            $table->dropForeign(['koperasi_id']);
            $table->dropColumn('koperasi_id');
        });
        
        Schema::dropIfExists('koperasis');
    }
};