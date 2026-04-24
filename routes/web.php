<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LoginController;

// --- GUEST ROUTES (Bisa diakses tanpa login) ---
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);


// --- AUTH ROUTES (Hanya bisa diakses jika sudah login) ---
Route::middleware(['auth'])->group(function () {
    
    // Auth Logic
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Home & Dashboard
    Route::get('/home', [ItemController::class, 'index'])->name('home');

    // Inventaris / Item Management
    Route::get('/tambah-item', [ItemController::class, 'createView'])->name('item.create');
    Route::post('/store-item', [ItemController::class, 'store'])->name('item.store');
    Route::get('/edit-inventaris', [ItemController::class, 'edit'])->name('item.edit');
    Route::put('/update-inventaris', [ItemController::class, 'update'])->name('item.update');
    Route::get('/check-item', [ItemController::class, 'check'])->name('item.check');
    Route::get('/laporan', [ItemController::class, 'report'])->name('item.laporan');

    // Cart & Transaction
    Route::post('/cart/add', [ItemController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart/clear', [ItemController::class, 'clearCart'])->name('cart.clear');
    Route::post('/checkout', [ItemController::class, 'checkout'])->name('cart.checkout');

});