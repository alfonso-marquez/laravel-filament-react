<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product; // Assuming you have a Product model

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/api/products', function () {
//     return Product::all();
// });

Route::get('/api/products', function () {
    return response()->json(Product::all());
});