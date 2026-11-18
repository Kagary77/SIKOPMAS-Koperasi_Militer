<?php

namespace App\Modules\Ordering\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Product;

final class CartService
{
    /**
     * Mendapatkan atau membuat keranjang aktif untuk pelanggan di koperasi tertentu.
     */
    public function getCart(Customer $customer, int $koperasiId): Cart
    {
        return Cart::firstOrCreate([
            'customer_id' => $customer->id,
            'koperasi_id' => $koperasiId,
        ]);
    }

    /**
     * Menambah produk ke keranjang.
     */
    public function addProduct(Customer $customer, int $koperasiId, Product $product, int $qty = 1): void
    {
        $cart = $this->getCart($customer, $koperasiId);

        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->increment('qty', $qty);
        } else {
            $cart->items()->create([
                'koperasi_id' => $koperasiId,
                'product_id' => $product->id,
                'qty' => $qty,
            ]);
        }
    }

    /**
     * Memperbarui jumlah (Qty) produk di keranjang.
     */
    public function updateQty(Customer $customer, int $koperasiId, int $productId, int $newQty): void
    {
        if ($newQty <= 0) {
            $this->removeProduct($customer, $koperasiId, $productId);
            return;
        }

        $cart = $this->getCart($customer, $koperasiId);
        $cart->items()->where('product_id', $productId)->update(['qty' => $newQty]);
    }

    /**
     * Menghapus produk dari keranjang.
     */
    public function removeProduct(Customer $customer, int $koperasiId, int $productId): void
    {
        $cart = $this->getCart($customer, $koperasiId);
        $cart->items()->where('product_id', $productId)->delete();
    }

    /**
     * Mengosongkan keranjang (dipanggil setelah checkout).
     */
    public function clearCart(Customer $customer, int $koperasiId): void
    {
        $cart = $this->getCart($customer, $koperasiId);
        $cart->items()->delete();
    }
}