<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

// Optional: Add more web routes if needed
Route::get('/api-test', function () {
    return view('api-test');
});