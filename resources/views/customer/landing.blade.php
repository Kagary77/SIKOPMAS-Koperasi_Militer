{{-- Ganti bagian tombol di dalam @foreach($koperasis as $koperasi) --}}
@auth('customer')
    <a href="{{ route('customer.catalog', $koperasi->slug) }}" class="rounded-lg bg-red-600 px-3 py-2 text-xs font-bold text-white">Masuk Toko</a>
@else
    <a href="{{ route('customer.catalog', $koperasi->slug) }}" class="rounded-lg border border-red-600 px-3 py-2 text-xs font-bold text-red-600">Lihat Katalog</a>
@endauth