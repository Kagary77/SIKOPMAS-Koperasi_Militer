<x-layouts.admin title="Dashboard Pusat">
    <h1 class="text-xl font-semibold">Dashboard Pengelola Pusat</h1>
    <div class="mt-6 overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-800">
        <table class="w-full min-w-[40rem] text-left text-sm">
            <thead class="bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                <tr>
                    <th class="px-4 py-2">Koperasi</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Total Transaksi</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-t border-zinc-200 dark:border-zinc-800">
                    <td class="px-4 py-3" colspan="3">
                        <x-empty-state 
                            title="Belum ada data koperasi" 
                            description="Data konsolidasi dari seluruh koperasi akan tampil di sini." 
                        />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</x-layouts.admin>