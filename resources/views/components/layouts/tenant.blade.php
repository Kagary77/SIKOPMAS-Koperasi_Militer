@props(['title' => 'Koperasi'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-zinc-50 text-zinc-900 dark:bg-zinc-950 dark:text-zinc-100">
    <div x-data="{ open: true }" class="flex min-h-screen">
        <!-- Sidebar -->
        <aside x-show="open" class="w-64 shrink-0 border-e border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900">
            <div class="flex h-14 items-center border-b border-zinc-200 px-4 font-bold dark:border-zinc-800">
                {{ config('app.name') }}
            </div>
            <nav class="space-y-1 p-2 text-sm">
                <a href="#" class="block rounded-lg px-3 py-2 bg-zinc-100 dark:bg-zinc-800 font-medium">Dashboard</a>
                <span class="block px-3 py-2 text-zinc-400">Katalog & Stok (Struktur 2)</span>
                <span class="block px-3 py-2 text-zinc-400">POS / Kasir (Struktur 2)</span>
                <span class="block px-3 py-2 text-zinc-400">Pesanan Online (Struktur 2)</span>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex min-w-0 flex-1 flex-col">
            <header class="flex min-h-14 items-center gap-3 border-b border-zinc-200 bg-white px-4 dark:border-zinc-800 dark:bg-zinc-900">
                <button type="button" @click="open = !open" class="inline-flex min-h-11 min-w-11 items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 lg:hidden" aria-label="Toggle sidebar">
                    ☰
                </button>
                <span class="font-semibold">{{ $title }}</span>
                @isset($headerRight)
                    <div class="ms-auto">{{ $headerRight }}</div>
                @endisset
            </header>
            <main class="flex-1 p-6">{{ $slot }}</main>
        </div>
    </div>
    @fluxScripts
</body>
</html>