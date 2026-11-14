<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Koperasi;
use App\Models\Product;
use App\Models\User;
use App\Support\Tenancy\KoperasiContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KoperasiIsolationTest extends TestCase
{
    use RefreshDatabase;

    private function koperasi(string $name): Koperasi
    {
        return Koperasi::create([
            'name' => $name,
            'slug' => strtolower($name),
            'code' => strtoupper($name),
            'status' => 'active'
        ]);
    }

    public function test_global_scope_hanya_menampilkan_data_koperasi_aktif(): void
    {
        $koperasiA = $this->koperasi('Toko A');
        $koperasiB = $this->koperasi('Toko B');

        // 1. Buat Kategori untuk masing-masing koperasi
        $kategoriA = Category::create(['koperasi_id' => $koperasiA->id, 'name' => 'Sembako']);
        $kategoriB = Category::create(['koperasi_id' => $koperasiB->id, 'name' => 'Minuman']);

        // 2. Buat produk di Koperasi A (Sertakan category_id)
        (new Product)->forceFill([
            'koperasi_id' => $koperasiA->id,
            'category_id' => $kategoriA->id,
            'name' => 'Beras A',
            'price' => 10000,
            'stock' => 10
        ])->save();

        // 3. Buat produk di Koperasi B
        (new Product)->forceFill([
            'koperasi_id' => $koperasiB->id,
            'category_id' => $kategoriB->id,
            'name' => 'Beras B',
            'price' => 12000,
            'stock' => 5
        ])->save();

        // 4. Set Context ke Koperasi A
        $context = app(KoperasiContext::class);
        $context->set($koperasiA);

        // 5. Query Product::all() harusnya hanya mengembalikan milik Koperasi A
        $products = Product::all();
        
        $this->assertCount(1, $products);
        $this->assertEquals('Beras A', $products->first()->name);
        
        // Bersihkan context
        $context->clear();
    }

    public function test_auto_fill_koperasi_id_saat_create(): void
    {
        $koperasiA = $this->koperasi('Toko A');
        app(KoperasiContext::class)->set($koperasiA);

        // Buat kategori (harus menyertakan koperasi_id karena dipanggil sebelum context di-set? 
        // Tidak, context sudah di-set di atas, jadi koperasi_id auto-fill)
        $kategori = Category::create(['name' => 'Makanan']);

        // Buat produk
        $product = Product::create([
            'category_id' => $kategori->id,
            'name' => 'Gula', 
            'price' => 15000, 
            'stock' => 20
        ]);

        $this->assertEquals($koperasiA->id, $product->koperasi_id);
        $this->assertEquals($koperasiA->id, $kategori->koperasi_id);
    }
}