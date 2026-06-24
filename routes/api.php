<?php

use App\Http\Controllers\Api\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Api\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\Admin\PromoController as AdminPromoController;
use App\Http\Controllers\Api\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Api\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HistoryController;
use App\Http\Controllers\Api\PosController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────────────
// API v1 — dipakai oleh aplikasi mobile (Flutter)
// ─────────────────────────────────────────────────────────────────
Route::prefix('v1')->group(function () {

    // ── AUTH (publik) ──
    Route::post('/login', [AuthController::class, 'login']);

    // ── AUTH (butuh token) ──
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        // ─────────────────────────────────────────────────────────
        // KASIR — Fase 1
        // ─────────────────────────────────────────────────────────
        Route::middleware('role:kasir|admin')->prefix('kasir')->group(function () {
            // Produk & kategori untuk katalog POS
            Route::get('/products', [ProductController::class, 'index']);

            // Data awal POS (promo aktif)
            Route::get('/pos/init', [PosController::class, 'init']);

            // Checkout
            Route::post('/checkout', [PosController::class, 'checkout']);

            // Customer search (autocomplete)
            Route::get('/customers/search', [PosController::class, 'searchCustomers']);

            // Riwayat transaksi kasir
            Route::get('/history', [HistoryController::class, 'index']);
            Route::get('/history/{transaction}', [HistoryController::class, 'show']);
            Route::patch('/history/{transaction}/toggle-status', [HistoryController::class, 'toggleStatus']);
        });

        // ─────────────────────────────────────────────────────────
        // ADMIN/OWNER — Fase 2
        // ─────────────────────────────────────────────────────────
        Route::middleware('role:admin,sanctum')->prefix('admin')->group(function () {

            // Dashboard
            Route::get('/dashboard', [AdminDashboardController::class, 'index']);

            // Produk (CRUD + upload gambar)
            Route::get('/products', [AdminProductController::class, 'index']);
            Route::post('/products', [AdminProductController::class, 'store']);
            Route::get('/products/{product}', [AdminProductController::class, 'show']);
            // Catatan: gunakan POST + _method=PUT (form multipart) dari Flutter untuk update dengan gambar.
            Route::match(['put', 'post'], '/products/{product}', [AdminProductController::class, 'update']);
            Route::delete('/products/{product}', [AdminProductController::class, 'destroy']);

            // Transaksi
            Route::get('/transactions', [AdminTransactionController::class, 'index']);
            Route::get('/transactions/{transaction}', [AdminTransactionController::class, 'show']);
            Route::patch('/transactions/{transaction}', [AdminTransactionController::class, 'update']);
            Route::patch('/transactions/{transaction}/toggle-status', [AdminTransactionController::class, 'toggleStatus']);

            // Laporan
            Route::get('/reports/sales', [AdminReportController::class, 'sales']);
            Route::get('/reports/products', [AdminReportController::class, 'products']);
            Route::get('/reports/customers', [AdminReportController::class, 'customers']);

            // Promo
            Route::get('/promos', [AdminPromoController::class, 'index']);
            Route::post('/promos', [AdminPromoController::class, 'store']);
            Route::patch('/promos/{promo}/toggle', [AdminPromoController::class, 'toggle']);
            Route::delete('/promos/{promo}', [AdminPromoController::class, 'destroy']);

            // Customer
            Route::get('/customers', [AdminCustomerController::class, 'index']);
            Route::post('/customers', [AdminCustomerController::class, 'store']);
            Route::delete('/customers/{customer}', [AdminCustomerController::class, 'destroy']);

            // Kelola Kasir
            Route::get('/kasirs', [AdminUserController::class, 'index']);
            Route::post('/kasirs', [AdminUserController::class, 'store']);
            Route::delete('/kasirs/{user}', [AdminUserController::class, 'destroy']);
        });
    });
});
