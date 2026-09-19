<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// Redirect root to POS cashier view
Route::get('/', function () {
    return redirect()->route('pos.index');
});

// POS / Kasir Routes
Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');

// Dashboard Routes
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Transaction History & Receipts
Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::get('/transactions/{order}', [TransactionController::class, 'show'])->name('transactions.show');
Route::get('/transactions/{order}/receipt', [TransactionController::class, 'receipt'])->name('transactions.receipt');

// Product & Menu Management
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::patch('/products/{product}/toggle', [ProductController::class, 'toggleStatus'])->name('products.toggle');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
