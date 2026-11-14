<?php

namespace App\Http\Middleware;

use App\Models\Koperasi;
use App\Support\Tenancy\KoperasiContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetKoperasiContext
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Ambil koperasi dari URL (Route Model Binding via slug)
        $koperasi = $request->route('koperasi');
        
        if (! $koperasi instanceof Koperasi) {
            $koperasi = Koperasi::where('slug', (string) $koperasi)->first();
        }

        if (! $koperasi) {
            abort(404);
        }

        if ($koperasi->status !== 'active') {
            abort(403, 'Koperasi tidak aktif.');
        }

        $user = $request->user();
        if ($user === null) {
            abort(403);
        }

        // 2. Verifikasi Membership: User harus terikat ke koperasi ini
        // (Super Admin Pusat tidak boleh masuk ke portal tenant dengan role koperasi)
        if ((int) $user->koperasi_id !== $koperasi->id) {
            abort(403, 'Anda tidak memiliki akses ke koperasi ini.');
        }

        // 3. Isi Context dan Lanjutkan Request
        $context = app(KoperasiContext::class);
        $context->set($koperasi);

        try {
            return $next($request);
        } finally {
            // 4. Bersihkan Context (Anti Kebocoran)
            $context->clear();
        }
    }
}