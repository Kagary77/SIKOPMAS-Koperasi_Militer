{{-- Di dalam tag <header> --}}
@auth('customer')
    <div class="flex items-center gap-3">
        <a href="{{ route('customer.complaints', request()->route('koperasi')) }}" class="text-xs font-bold text-zinc-600 hover:text-red-600">Pengaduan</a>
        {{-- ... tombol Keluar ... --}}
    </div>
@endauth