<?php

use App\Models\Cart;
use App\Modules\Ordering\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public int $koperasiId;

    public function mount(int $koperasiId): void
    {
        $this->koperasiId = $koperasiId;
    }

    #[Computed]
    public function cart()
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer) return null;

        return app(CartService::class)->getCart($customer, $this->koperasiId)->load('items.product');
    }

    #[Computed]
    public function total()
    {
        if (!$this->cart) return 0;
        return $this->cart->items->sum(fn ($item) => $item->product->price * $item->qty);
    }

    public function updateQty(int $productId, int $qty): void
    {
        app(CartService::class)->updateQty(
            Auth::guard('customer')->user(), 
            $this->koperasiId, 
            $productId, 
            $qty
        );
        unset($this->cart, $this->total); // Refresh UI
    }

    public function remove(int $productId): void
    {
        app(CartService::class)->removeProduct(
            Auth::guard('customer')->user(), 
            $this->koperasiId, 
            $productId
        );
        unset($this->cart, $this->total);
    }
};
?>

<div class="space-y-4">
    @if(!$this->cart || $this->cart->items->isEmpty())
        <div class="rounded-xl border border-dashed border-zinc-300 p-10 text-center text-zinc-500">
            Keranjang Anda masih kosong.
        </div>
    @else
        <div class="space-y-3">
            @foreach($this->cart->items as $item)
                <div wire:key="item-{{ $item->id }}" class="flex items-center gap-3 rounded-xl border border-zinc-200 bg-white p-3">
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-zinc-900">{{ $item->product->name }}</h4>
                        <p class="text-sm font-semibold text-red-600">Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <button wire:click="updateQty({{ $item->product_id }}, {{ $item->qty - 1 }})" class="flex size-8 items-center justify-center rounded-lg border border-zinc-300 font-bold text-zinc-600">&minus;</button>
                        <span class="w-6 text-center text-sm font-bold">{{ $item->qty }}</span>
                        <button wire:click="updateQty({{ $item->product_id }}, {{ $item->qty + 1 }})" class="flex size-8 items-center justify-center rounded-lg border border-zinc-300 font-bold text-zinc-600">&plus;</button>
                    </div>
                    
                    <button wire:click="remove({{ $item->product_id }})" class="text-xs text-red-600 underline">Hapus</button>
                </div>
            @endforeach
        </div>

        <div class="rounded-xl bg-zinc-100 p-4">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-zinc-600">Total Belanja</span>
                <span class="text-lg font-extrabold text-red-600">Rp {{ number_format($this->total, 0, ',', '.') }}</span>
            </div>
        </div>

        <a href="{{ route('customer.checkout', ['koperasi' => request()->route('koperasi')]) }}" class="block w-full rounded-lg bg-red-600 py-3 text-center text-sm font-bold text-white hover:bg-red-700">
            Lanjut ke Checkout
        </a>
    @endif
</div>