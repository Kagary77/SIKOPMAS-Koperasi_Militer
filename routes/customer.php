<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function ($koperasi) {
    return view('customer.home', ['koperasi' => $koperasi]);
})->name('home');