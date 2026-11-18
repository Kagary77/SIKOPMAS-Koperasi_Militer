<?php

use App\Models\Cart;
use App\Modules\Ordering\Services\CheckoutService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public int $koperasiId;
    public string $notes = '';

    public function mount(int $koperasiId): void
    {
        $this->koperasiId = $koperasiId;
    }

    #[Computed]
    public function cart()
    {
        return Cart::where('customer_id', Auth::guard('customer')->id())
            ->where('koperasi_id', $this->koperasiId)
            ->with('items.product')
            ->first();
    }

    #[Computed]
    public function total()
    {
        return $this->cart ? $this->cart->items->sum(fn ($item) => $item->product->price * $item->qty) : 0;
    }

    public function processCheckout()
    {
        try {
            $order = app(CheckoutService::class)->checkout(
                Auth::guard('customer')->user(),
                \App\Models\Koperasi::findOrFail($this->koperasiId),
                $this->notes
            );

            // Redirect ke halaman pembayaran/pelacakan (Iterasi 4)
            return redirect()->route('customer.order.show', [
                'koperasi' => request()->route('koperasi'),
                'orderNumber' => $order->order_number
            ]);
            
        } catch (\Exception $e) {
            $this->addError('checkout', $e->getMessage());
        }
    }
};
?>

<div class="space-y-4">
    <div class="rounded-xl border border-zinc-200 bg-white p-4">
        <h3 class="font-bold text-zinc-900 mb-3">Ringkasan Pesanan</h3>
        @foreach($this->cart->items as $item)
            <div class="flex justify-between py-1 text-sm">
                <span>{{ $item->qty }}x {{ $item->product->name }}</span>
                <span class="font-semibold">Rp {{ number_format($item->product->price * $item->qty, 0, ',', '.') }}</span>
            </div>
        @endforeach
        <div class="mt-3 border-t pt-3 flex justify-between font-extrabold text-red-600">
            <span>Total</span>
            <span>Rp {{ number_format($this->total, 0, ',', '.') }}</span>
        </div>
    </div>

    <div>
        <label class="block text-sm font-semibold text-zinc-700">Catatan (Opsional)</label>
        <textarea wire:model="notes" rows="2" class="mt-1 w-full rounded-lg border border-zinc-300 px-3 py-2 focus:border-red-500" placeholder="Misal: Ambil sore hari..."></textarea>
    </div>

    @error('checkout')
        <div class="rounded-lg bg-red-100 p-3 text-sm text-red-800">{{ $message }}</div>
    @enderror

    <button 
        wire:click="processCheckout" 
        wire:loading.attr="disabled"
        class="w-full rounded-lg bg-red-600 py-3 text-sm font-bold text-white hover:bg-red-700 disabled:opacity-50"
    >
        Buat Pesanan & Lanjut Bayar
    </button>
</div>