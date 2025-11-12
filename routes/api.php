<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;

Route::get('/products/{id}', [ProductApiController::class, 'getProduct']);
Route::get('/products/search', [ProductApiController::class, 'searchProducts']);