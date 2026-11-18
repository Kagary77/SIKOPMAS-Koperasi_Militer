<x-layouts.customer :title="'Checkout - ' . $koperasi->name">
    <h1 class="mb-4 text-xl font-extrabold">Konfirmasi Pesanan</h1>
    <livewire:ordering::checkout-form :koperasiId="$koperasi->id" />
</x-layouts.customer>