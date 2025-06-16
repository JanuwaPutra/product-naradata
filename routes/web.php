<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

// Dashboard route
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Products routes
Route::resource('products', ProductController::class);
Route::get('products-export-excel', [ProductController::class, 'exportExcel'])->name('products.export.excel');
Route::get('products-export-pdf', [ProductController::class, 'exportPdf'])->name('products.export.pdf');
Route::get('products-import', [ProductController::class, 'importForm'])->name('products.import.form');
Route::post('products-import', [ProductController::class, 'import'])->name('products.import');
Route::get('products-template', [ProductController::class, 'downloadTemplate'])->name('products.template');

// Sales routes
Route::resource('sales', SaleController::class);
Route::get('sales-export-excel', [SaleController::class, 'exportExcel'])->name('sales.export.excel');
Route::get('sales-export-pdf', [SaleController::class, 'exportPdf'])->name('sales.export.pdf');
Route::get('sales-import', [SaleController::class, 'importForm'])->name('sales.import.form');
Route::post('sales-import', [SaleController::class, 'import'])->name('sales.import');
Route::get('sales-template', [SaleController::class, 'downloadTemplate'])->name('sales.template');
