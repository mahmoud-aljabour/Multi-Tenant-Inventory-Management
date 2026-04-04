<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/init-db', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate --force');
    return "تم بناء كل الجداول.. مبروك!";
});
