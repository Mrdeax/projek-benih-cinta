<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('admin_or_officer')->group(function () {
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::get('/create', [ProductController::class, 'create'])->name('create');
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
            Route::put('/{product}', [ProductController::class, 'update'])->name('update');
            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('sales')->name('sales.')->group(function () {
            Route::get('/', [SalesController::class, 'index'])->name('index');
            Route::get('/create', [SalesController::class, 'create'])->name('create');
            Route::post('/', [SalesController::class, 'store'])->name('store');
            Route::get('/{sale}', [SalesController::class, 'show'])->name('show');
            Route::get('/{sale}/invoice', [SalesController::class, 'invoice'])->name('invoice');
        });

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/stock', [ReportController::class, 'stockReport'])->name('stock');
            Route::get('/sales', [ReportController::class, 'salesReport'])->name('sales');
            Route::get('/sales/pdf', [ReportController::class, 'salesPdf'])->name('sales-pdf');
            Route::get('/stock/pdf', [ReportController::class, 'stockPdf'])->name('stock-pdf');
        });
    });

    Route::middleware('admin')->group(function () {
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('member-discounts')->name('discounts.')->group(function () {
            Route::get('/', [ReportController::class, 'discountIndex'])->name('index');
            Route::get('/create', [ReportController::class, 'discountCreate'])->name('create');
            Route::post('/', [ReportController::class, 'discountStore'])->name('store');
            Route::get('/{discount}/edit', [ReportController::class, 'discountEdit'])->name('edit');
            Route::put('/{discount}', [ReportController::class, 'discountUpdate'])->name('update');
            Route::delete('/{discount}', [ReportController::class, 'discountDestroy'])->name('destroy');
        });
    });
});