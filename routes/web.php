<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DuitkuCallbackController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// User / Public Routes
Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/menu', [PageController::class, 'menu'])->name('menu');
Route::get('/kontak', [PageController::class, 'contact'])->name('contact');
Route::get('/pesanan', [PageController::class, 'orders'])->name('orders');
Route::get('/riwayat-pemesanan', [PageController::class, 'orderHistory'])->name('order.history');
Route::get('/invoice/{id}', [InvoiceController::class, 'download']);

// User Auth
Route::get('/login', [UserAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [UserAuthController::class, 'login'])->name('login.post');
Route::get('/daftar', [UserAuthController::class, 'showRegister'])->name('register');
Route::post('/daftar', [UserAuthController::class, 'register'])->name('register.post');
Route::match(['get', 'post'], '/logout', [UserAuthController::class, 'logout'])->name('logout');

// API: polling status pesanan (real-time)
Route::post('/api/order-status', [PageController::class, 'apiOrderStatus'])->name('api.order.status');

// Duitku Callback
Route::post('/callback', [DuitkuCallbackController::class, 'handle']);

// Cart Routes
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'get'])->name('get');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/remove', [CartController::class, 'remove'])->name('remove');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::middleware('auth')->post('/checkout', [CartController::class, 'checkout'])->name('checkout');
});

// Admin Auth
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::match(['get', 'post'], '/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');

        // Stok & Menu
        Route::get('/stok', [AdminController::class, 'stok'])->name('stok');
        Route::get('/menu/create', [AdminController::class, 'createMenu'])->name('menu.create');
        Route::post('/menu', [AdminController::class, 'storeMenu'])->name('menu.store');
        Route::get('/menu/{menu}/edit', [AdminController::class, 'editMenu'])->name('menu.edit');
        Route::put('/menu/{menu}', [AdminController::class, 'updateMenu'])->name('menu.update');
        Route::delete('/menu/{menu}', [AdminController::class, 'destroyMenu'])->name('menu.destroy');

        // Pesanan
        Route::get('/pesanan', [AdminController::class, 'pesanan'])->name('pesanan');
        Route::patch('/pesanan/{order}/status', [AdminController::class, 'updateStatusPesanan'])->name('pesanan.status');
        Route::post('/pesanan/{order}/konfirmasi-pembayaran', [AdminController::class, 'konfirmasiPembayaran'])->name('pesanan.konfirmasi_pembayaran');
        Route::get('/api/pending-count', [AdminController::class, 'apiPendingCount'])->name('api.pending');
    });
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});
