<?php

namespace App\Modules\Ordering\Services;

use App\Models\Customer;
use App\Models\Koperasi;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

final class CheckoutService
{
    public function __construct(private CartService $cartService) {}

    public function checkout(Customer $customer, Koperasi $koperasi, ?string $notes = null): Order
    {
        return DB::transaction(function () use ($customer, $koperasi, $notes) {
            $cart = $this->cartService->getCart($customer, $koperasi->id)->load('items.product');

            if ($cart->items->isEmpty()) {
                throw new Exception('Keranjang belanja kosong.');
            }

            // 1. LOCK FOR UPDATE (Mencegah race condition stok)
            $productIds = $cart->items->pluck('product_id')->toArray();
            $products = Product::whereIn('id', $productIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $totalAmount = 0;
            $orderItemsData = [];

            // 2. VALIDASI STOK
            foreach ($cart->items as $item) {
                $product = $products[$item->product_id];

                if ($product->stock < $item->qty) {
                    throw new Exception("Stok produk {$product->name} tidak mencukupi (Sisa: {$product->stock}).");
                }

                $subtotal = $product->price * $item->qty;
                $totalAmount += $subtotal;

                $orderItemsData[] = [
                    'koperasi_id' => $koperasi->id,
                    'product_id' => $product->id,
                    'product_name_snapshot' => $product->name,
                    'price_snapshot' => $product->price,
                    'qty' => $item->qty,
                    'subtotal' => $subtotal,
                ];

                // 3. POTONG STOK (Mutasi)
                $product->decrement('stock', $item->qty);
            }

            // 4. BUAT ORDER
            $order = Order::create([
                'koperasi_id' => $koperasi->id,
                'customer_id' => $customer->id,
                'order_number' => 'ORD-' . date('ymd') . '-' . strtoupper(Str::random(5)),
                'total_amount' => $totalAmount,
                'status' => 'pending_payment',
                'payment_deadline' => now()->addMinutes(15),
                'notes' => $notes,
            ]);

            // 5. SIMPAN ORDER ITEMS
            $order->items()->createMany($orderItemsData);

            // 6. KOSONGKAN KERANJANG
            $this->cartService->clearCart($customer, $koperasi->id);

            return $order;
        });
    }
}