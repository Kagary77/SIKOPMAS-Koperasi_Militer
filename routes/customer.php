Route::middleware('auth:customer')->group(function () {
    // ... route cart & checkout ...
    
    // Rute Pengaduan
    Route::get('/{koperasi:slug}/complaints', function (\App\Models\Koperasi $koperasi) {
        return view('ordering::customer.complaints', compact('koperasi'));
    })->name('complaints');
});