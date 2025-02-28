<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Http\Controllers\Api\QuotationController;
use App\Http\Controllers\Api\ProductController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/api/products', function () {
//     return Product::all();
// });

Route::get('/api/products', [ProductController::class, 'index']);
Route::post('/api/quotations', [QuotationController::class, 'store']);
