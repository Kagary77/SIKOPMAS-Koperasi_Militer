<x-layouts.customer :title="$koperasi->name">
    <div class="mb-4 rounded-xl bg-red-600 p-4 text-white shadow-md">
        <h1 class="text-xl font-extrabold">{{ $koperasi->name }}</h1>
        <p class="text-xs opacity-90">{{ $koperasi->address ?? 'Koperasi Militer' }}</p>
    </div>

    <livewire:catalog::product-catalog :koperasi="$koperasi" />
</x-layouts.customer>