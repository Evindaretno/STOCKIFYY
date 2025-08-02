<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/produk', function () {
    return view('produk');
});

Route::get('/stok', function () {
    return view('stok');
});

Route::get('/users', function () {
    return view('users');
});

Route::get('/laporan', function () {
    return view('laporan');
});

Route::get('/pengaturan', function () {
    return view('pengaturan');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});