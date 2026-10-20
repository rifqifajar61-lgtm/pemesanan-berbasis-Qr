<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Cashier\CashierController;
use App\Http\Controllers\Kitchen\KitchenController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuManagementController;
use App\Http\Controllers\Admin\CategoryManagementController;
use App\Http\Controllers\Admin\EmployeeManagementController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\PromoManagementController;
use App\Http\Controllers\Admin\TableManagementController;
use App\Http\Controllers\Auth\AuthController;

// Auth Routes (Login & Register Pegawai)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Alamat utama: belum login ke halaman login, sudah login ke dashboard sesuai peran
Route::get('/', function () {
    if (auth()->check()) {
        return match (auth()->user()->role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'cashier' => redirect()->route('cashier.dashboard'),
            'kitchen' => redirect()->route('kitchen.index'),
            default   => redirect()->route('home'),
        };
    }

    return redirect()->route('login');
});

// ==========================================
// 1. ROUTE PELANGGAN (PUBLIC / CUSTOMER)
// ==========================================
Route::get('/menu', [MenuController::class, 'index'])->name('home');
Route::post('/checkout', [OrderController::class, 'checkout'])->name('order.checkout');
Route::get('/order/{orderCode}', [OrderController::class, 'show'])->name('order.show');

// ==========================================
// 2. ROUTE KASIR
// ==========================================
Route::middleware(['auth', 'role:cashier,admin'])->prefix('cashier')->name('cashier.')->group(function () {
    Route::get('/dashboard', [CashierController::class, 'dashboard'])->name('dashboard');
    Route::get('/scan', [CashierController::class, 'scan'])->name('scan');
    Route::post('/lookup', [CashierController::class, 'lookup'])->name('lookup');
    Route::get('/process/{orderCode}', [CashierController::class, 'process'])->name('process');
    Route::post('/pay-cash/{id}', [CashierController::class, 'payCash'])->name('payCash');
    Route::post('/pay-qris/{id}', [CashierController::class, 'payQris'])->name('payQris');
    Route::get('/success/{id}', [CashierController::class, 'success'])->name('success');
    Route::get('/history', [CashierController::class, 'history'])->name('history');
});

// ==========================================
// 3. ROUTE DAPUR
// ==========================================
Route::middleware(['auth', 'role:kitchen,admin'])->prefix('kitchen')->name('kitchen.')->group(function () {
    Route::get('/', fn () => redirect()->route('kitchen.dashboard'))->name('index');
    Route::get('/dashboard', [KitchenController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [KitchenController::class, 'orders'])->name('orders');
    Route::get('/processing', [KitchenController::class, 'processing'])->name('processing');
    Route::get('/ready', [KitchenController::class, 'ready'])->name('ready');
    Route::get('/history', [KitchenController::class, 'history'])->name('history');
    Route::post('/status/{id}', [KitchenController::class, 'updateStatus'])->name('update-status');
});

// ==========================================
// 4. ROUTE ADMIN
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Menu (CRUD)
    Route::resource('menus', MenuManagementController::class);

    // Manajemen Kategori (CRUD)
    Route::resource('categories', CategoryManagementController::class)->except(['show']);

    // Manajemen Pegawai (CRUD)
    Route::resource('employees', EmployeeManagementController::class)->except(['show']);

    // Manajemen Pesanan (daftar & detail)
    Route::resource('orders', OrderManagementController::class)->only(['index', 'show']);

    // Laporan Penjualan
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Manajemen Promo (CRUD)
    Route::resource('promos', PromoManagementController::class)->except(['show']);

    // Manajemen Meja & QR Code (CRUD)
    Route::resource('tables', TableManagementController::class)->except(['show']);
});