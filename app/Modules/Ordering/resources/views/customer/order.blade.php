<x-layouts.customer :title="'Pesanan ' . $order->order_number">
    <div class="mb-4">
        <a href="{{ route('customer.catalog', $koperasi->slug) }}" class="text-sm font-bold text-red-600">&larr; Kembali ke Katalog</a>
    </div>
    
    <livewire:ordering::order-status :order="$order" />
</x-layouts.customer>