<?php
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function (string $koperasi) {
    return view('tenant.dashboard', ['koperasi' => $koperasi]);
})->name('dashboard');