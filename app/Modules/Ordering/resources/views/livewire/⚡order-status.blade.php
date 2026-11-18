<?php

use App\Models\Order;
use App\Modules\Payments\Services\PaymentService;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        $this->order = $order;
    }

    #[Computed]
    public function payment()
    {
        if ($this->order->status === 'pending_payment') {
            return app(PaymentService::class)->initiate($this->order);
        }
        return $this->order->payment;
    }

    public function simulatePay(): void
    {
        if ($this->payment) {
            app(PaymentService::class)->simulatePayment($this->payment);
            $this->order->refresh();
            unset($this->payment);
        }
    }
};
?>

{{-- wire:poll.3s akan memperbarui data setiap 3 detik (sebagai pengganti WebSocket sementara) --}}
<div class="space-y-6" wire:poll.3s="refreshOrder">
    <div class="rounded-xl border border-zinc-200 bg-white p-4 text-center">
        <p class="text-xs text-zinc-500">Nomor Pesanan</p>
        <p class="text-lg font-bold text-zinc-900">{{ $order->order_number }}</p>
        
        {{-- Visualisasi Status (Timeline) --}}
        <div class="mt-6 flex items-center justify-between px-2 text-xs font-semibold">
            @php
                $statuses = [
                    'paid' => 'Dibayar',
                    'processing' => 'Diproses',
                    'ready_for_pickup' => 'Siap',
                    'completed' => 'Selesai'
                ];
                $currentStatusIndex = array_search($order->status, array_keys($statuses));
                if ($order->status === 'pending_payment') $currentStatusIndex = -1;
            @endphp

            @foreach($statuses as $key => $label)
                @php $isActive = $currentStatusIndex >= $loop->index; @endphp
                <div class="flex flex-col items-center gap-1">
                    <div class="flex size-8 items-center justify-center rounded-full {{ $isActive ? 'bg-red-600 text-white' : 'bg-zinc-200 text-zinc-500' }}">
                        @if($isActive) ✓ @else {{ $loop->iteration }} @endif
                    </div>
                    <span class="{{ $isActive ? 'text-red-600' : 'text-zinc-400' }}">{{ $label }}</span>
                </div>
                @if(!$loop->last)
                    <div class="h-0.5 flex-1 {{ $currentStatusIndex > $loop->index ? 'bg-red-600' : 'bg-zinc-200' }}"></div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- Konten Pembayaran (Jika Belum Bayar) --}}
    @if($order->status === 'pending_payment' && $this->payment)
        <div class="rounded-xl border border-zinc-200 bg-white p-4 text-center">
            <h2 class="font-bold text-zinc-900 mb-2">Pembayaran QRIS</h2>
            <p class="text-sm text-zinc-500 mb-4">Total: <span class="font-bold text-red-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></p>
            
            <div class="mx-auto mb-4 flex h-48 w-48 items-center justify-center border-2 border-zinc-900 bg-white p-2">
                <div class="text-center">
                    <p class="text-xs font-bold text-zinc-400">QRIS PLACEHOLDER</p>
                </div>
            </div>
            
            <div class="border-t border-dashed pt-4">
                <button wire:click="simulatePay" wire:loading.attr="disabled" class="w-full rounded-lg border-2 border-dashed border-red-600 py-3 text-sm font-bold text-red-600 hover:bg-red-50">
                    Simulasi Bayar (Klik di sini)
                </button>
            </div>
        </div>
    @elseif($order->status !== 'pending_payment')
        <div class="rounded-xl border border-green-200 bg-green-50 p-6 text-center">
            <h2 class="text-lg font-bold text-green-800">Status: {{ strtoupper(str_replace('_', ' ', $order->status)) }}</h2>
            <p class="mt-1 text-sm text-green-700">Silakan ambil pesanan Anda di Koperasi.</p>
        </div>
    @endif

    {{-- Rincian Pesanan --}}
    <div class="rounded-xl border border-zinc-200 bg-white p-4">
        <h3 class="font-bold text-zinc-900 mb-3">Rincian Pesanan</h3>
        <ul class="space-y-2 text-sm text-zinc-600">
            @foreach($order->items as $item)
                <li class="flex justify-between">
                    <span>{{ $item->qty }}x {{ $item->product_name_snapshot }}</span>
                    <span class="font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </li>
            @endforeach
        </ul>
        <div class="mt-3 border-t pt-3 flex justify-between font-extrabold text-zinc-900">
            <span>Total</span>
            <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
        </div>
    </div>
</div>