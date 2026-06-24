<?php
// FILE: routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Kasir;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SuperAdmin\SubscriptionController as SaSubscriptionController;
use App\Http\Controllers\MidtransNotificationController;

Route::post('/midtrans/notification', [MidtransNotificationController::class, 'handle'])
    ->name('midtrans.notification')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Redirect root
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();

        if ($user->hasRole('superadmin')) {
            return redirect()->route('sa.dashboard');
        }

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('kasir.pos');
    }

    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store'])->name('register');
});

// Auth routes dari Breeze
require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {

    // ─────────────────────────────────────────────────────────────────
    // SUPER ADMIN
    // ─────────────────────────────────────────────────────────────────
    Route::prefix('sa')->name('sa.')->middleware('role:superadmin')->group(function () {
        Route::get('/dashboard',   [SuperAdmin\DashboardController::class, 'index'])->name('dashboard');
        Route::post('/superadmin/sync-revenue', [App\Http\Controllers\SuperAdmin\DashboardController::class, 'syncRevenue'])
            ->name('sa.sync.revenue');
        // Manajemen Bisnis
        Route::get('/businesses',  [SuperAdmin\BusinessController::class, 'index'])->name('businesses.index');
        Route::post('/businesses', [SuperAdmin\BusinessController::class, 'store'])->name('businesses.store');
        Route::patch('/businesses/{business}/toggle-status', [SuperAdmin\BusinessController::class, 'toggleStatus'])->name('businesses.toggle-status');
        Route::patch('/businesses/{business}/toggle-stok',   [SuperAdmin\BusinessController::class, 'toggleFeatStok'])->name('businesses.toggle-stok');
        Route::delete('/businesses/{business}',              [SuperAdmin\BusinessController::class, 'destroy'])->name('businesses.destroy');

        // Pengguna
        Route::get('/users',                       [SuperAdmin\UserController::class, 'index'])->name('users.index');
        Route::post('/users',                      [SuperAdmin\UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}',                [SuperAdmin\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}',             [SuperAdmin\UserController::class, 'destroy'])->name('users.destroy');

        // Fitur & Monitoring
        Route::get('/features', [SuperAdmin\FeatureController::class, 'index'])->name('features');
        Route::get('/monitor',  [SuperAdmin\MonitorController::class, 'index'])->name('monitor');

        // Subscription / Konfirmasi Pembayaran
        Route::get('/subscriptions',                          [SuperAdmin\SubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::patch('/subscriptions/{subscription}/approve', [SuperAdmin\SubscriptionController::class, 'approve'])->name('subscriptions.approve');
        Route::patch('/subscriptions/{subscription}/reject',  [SuperAdmin\SubscriptionController::class, 'reject'])->name('subscriptions.reject');
    });

    // ─────────────────────────────────────────────────────────────────
    // ADMIN
    // ─────────────────────────────────────────────────────────────────
    Route::prefix('admin')->name('admin.')->middleware(['role:admin', 'subscription'])->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/products',            [Admin\ProductController::class, 'index'])->name('products.index');
        Route::post('/products',           [Admin\ProductController::class, 'store'])->name('products.store');
        Route::put('/products/{product}',  [Admin\ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}',[Admin\ProductController::class, 'destroy'])->name('products.destroy');

        Route::get('/transactions',                          [Admin\TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{transaction}',            [Admin\TransactionController::class, 'show'])->name('transactions.show');
        Route::patch('/transactions/{transaction}',          [Admin\TransactionController::class, 'update'])->name('transactions.update');
        Route::patch('/transactions/{transaction}/toggle',   [Admin\TransactionController::class, 'toggleStatus'])->name('transactions.toggle');

        Route::get('/reports/sales',     [Admin\ReportController::class, 'sales'])->name('reports.sales');

        // Fitur yang butuh paket Pro/Business
        Route::middleware('feature:laporan_lengkap')->group(function () {
            Route::get('/reports/products',  [Admin\ReportController::class, 'products'])->name('reports.products');
            Route::get('/reports/customers', [Admin\ReportController::class, 'customers'])->name('reports.customers');
        });

        Route::middleware('feature:promo')->group(function () {
            Route::get('/promos',                  [Admin\PromoController::class, 'index'])->name('promos.index');
            Route::post('/promos',                 [Admin\PromoController::class, 'store'])->name('promos.store');
            Route::patch('/promos/{promo}/toggle', [Admin\PromoController::class, 'toggle'])->name('promos.toggle');
            Route::delete('/promos/{promo}',       [Admin\PromoController::class, 'destroy'])->name('promos.destroy');
        });

        Route::middleware('feature:customer')->group(function () {
            Route::get('/customers',               [Admin\CustomerController::class, 'index'])->name('customers.index');
            Route::post('/customers',              [Admin\CustomerController::class, 'store'])->name('customers.store');
            Route::delete('/customers/{customer}', [Admin\CustomerController::class, 'destroy'])->name('customers.destroy');
        });

        Route::get('/kasirs',            [Admin\UserController::class, 'index'])->name('kasirs.index');
        Route::post('/kasirs',           [Admin\UserController::class, 'store'])->name('kasirs.store');
        Route::delete('/kasirs/{user}',  [Admin\UserController::class, 'destroy'])->name('kasirs.destroy');
        
        Route::prefix('midtrans-setting')->name('midtrans-setting.')->group(function () {
            Route::get('/',        [Admin\MidtransSettingController::class, 'index'])->name('index');
            Route::post('/',       [Admin\MidtransSettingController::class, 'store'])->name('store');
            Route::delete('/',     [Admin\MidtransSettingController::class, 'disconnect'])->name('disconnect');
        });
        Route::prefix('subscription')->name('subscription.')->group(function () {
            Route::get('/',            [Admin\SubscriptionController::class, 'index'])->name('index');
            Route::post('/free',       [Admin\SubscriptionController::class, 'storeFree'])->name('store-free');
            Route::post('/snap-token', [Admin\SubscriptionController::class, 'createSnapToken'])->name('snap-token');
            Route::post('/manual',     [Admin\SubscriptionController::class, 'storeManual'])->name('store-manual');
        });
    });

    // ─────────────────────────────────────────────────────────────────
    // KASIR
    // ─────────────────────────────────────────────────────────────────
    Route::prefix('kasir')->name('kasir.')->middleware(['role:kasir|admin', 'subscription'])->group(function () {
        Route::get('/pos',                            [Kasir\PosController::class, 'index'])->name('pos');
        Route::post('/checkout',                      [Kasir\PosController::class, 'checkout'])->name('checkout');
        Route::get('/customers/search',               [Kasir\PosController::class, 'searchCustomers'])->name('customers.search');

        Route::get('/history',                        [Kasir\HistoryController::class, 'index'])->name('history');

        Route::get('/receipt/{transaction}',          [Kasir\ReceiptController::class, 'show'])->name('receipt');
        Route::get('/receipt/{transaction}/pdf',      [Kasir\ReceiptController::class, 'pdf'])->name('receipt.pdf');
        Route::patch('/receipt/{transaction}/catatan',[Kasir\ReceiptController::class, 'updateCatatan'])->name('receipt.catatan');
        Route::patch('/history/{transaction}/toggle-status',            [Kasir\HistoryController::class, 'toggleStatus'])->name('history.toggle-status');
        Route::get('/history/{transaction}/modal-struk',                [Kasir\HistoryController::class, 'modalStruk'])->name('history.modal-struk');
        Route::get('/history/{transaction}/send-wa', [Kasir\HistoryController::class, 'sendWa'])->name('history.send-wa');
        Route::post('/pos/snap-token', [Kasir\PosController::class, 'getSnapToken'])->name('pos.snap-token');
        Route::get('/akses-terkunci', function () {
            return view('kasir.subscription-locked');
        })->name('subscription-locked');
    });

});