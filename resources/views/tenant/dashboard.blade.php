<x-layouts.tenant title="Dashboard Koperasi">
    <div class="flex items-center gap-3">
        <h1 class="text-xl font-semibold">Dashboard Koperasi</h1>
        <x-status-badge status="active">Aktif</x-status-badge>
    </div>
    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
        Koperasi: <span class="font-mono">{{ $koperasi }}</span>
    </p>
    <div class="mt-6">
        <x-empty-state 
            title="Belum ada aktivitas" 
            description="Modul POS, Katalog, dan Laporan akan tampil di sini pada tahap selanjutnya." 
        />
    </div>
</x-layouts.tenant>