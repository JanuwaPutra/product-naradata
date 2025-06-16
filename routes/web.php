<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

// Dashboard route
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Products routes
Route::resource('products', ProductController::class);

// Sales routes
Route::resource('sales', SaleController::class);
