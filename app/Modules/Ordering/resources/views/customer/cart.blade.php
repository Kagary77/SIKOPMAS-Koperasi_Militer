<x-layouts.customer :title="'Keranjang - ' . $koperasi->name">
    <div class="mb-4 flex items-center gap-2">
        <a href="{{ route('customer.catalog', $koperasi->slug) }}" class="text-sm font-bold text-red-600">&larr; Kembali ke Katalog</a>
    </div>
    
    <h1 class="mb-4 text-xl font-extrabold">Keranjang Belanja</h1>

    <livewire:ordering::cart-page :koperasiId="$koperasi->id" />
</x-layouts.customer>