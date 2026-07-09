<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ProfitWithdrawalController;
use App\Http\Controllers\PayableController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImportController;
use App\Http\Controllers\ReceivableController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ServiceFeeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ---------- লগইন/লগআউট (গেস্টদের জন্য) ----------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email')->middleware('throttle:3,1');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ---------- ভাষা বদল (বাংলা/ইংলিশ) - লগইন করা না থাকলেও কাজ করবে ----------
Route::post('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

// ---------- লগইন করা সব ইউজারের জন্য (auth + current shop resolve) ----------
Route::middleware(['auth', 'current.shop'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/shops/switch', [ShopController::class, 'switch'])->name('shops.switch');

    Route::get('/change-password', [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'updatePassword'])->name('password.update');

    // ---------- শপ ম্যানেজমেন্ট: Super Admin ও Shop Owner দেখতে পারবে, তৈরি/ডিলিট শুধু Super Admin ----------
    Route::prefix('shops')->name('shops.')->middleware('role:super_admin,shop_owner')->group(function () {
        Route::get('/', [ShopController::class, 'index'])->name('index');
        Route::post('/', [ShopController::class, 'store'])->name('store');
        Route::delete('/{shop}', [ShopController::class, 'destroy'])->name('destroy');
    });

    // ---------- ইউজার (Shop Owner / Manager / Employee) ম্যানেজমেন্ট ----------
    Route::prefix('users')->name('users.')->middleware('role:super_admin,shop_owner')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    // ---------- প্রোডাক্ট: 'products' পারমিশন লাগবে (Manager/Employee এর জন্য) ----------
    Route::resource('products', ProductController::class)->except(['show'])
        ->middleware('shop.permission:products');
    Route::post('/products/{product}/restock', [ProductController::class, 'restock'])
        ->name('products.restock')->middleware('shop.permission:products');

    // ---------- ক্যাটাগরি: 'categories' পারমিশন লাগবে ----------
    Route::prefix('categories')->name('categories.')->middleware('shop.permission:categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    // ---------- স্টক: 'stock' পারমিশন লাগবে ----------
    Route::prefix('stock')->name('stock.')->middleware('shop.permission:stock')->group(function () {
        Route::get('/', [StockMovementController::class, 'index'])->name('index');
        Route::get('/create', [StockMovementController::class, 'create'])->name('create');
        Route::post('/', [StockMovementController::class, 'store'])->name('store');
        Route::get('/ledger', [StockMovementController::class, 'dailyLedger'])->name('ledger');
    });

    // ---------- দেনা-পাওনা / আর্থিক হিসাব: শুধু Super Admin ও Shop Owner দেখতে পারবে ----------
    Route::prefix('finance')->name('finance.')->middleware('role:super_admin,shop_owner')->group(function () {
        Route::get('/', [FinanceController::class, 'index'])->name('index');
        Route::post('/cash', [FinanceController::class, 'updateCash'])->name('update-cash');
        Route::post('/receivables/{sale}/payment', [SaleController::class, 'recordDuePayment'])->name('receivables.payment');
    });

    Route::prefix('payables')->name('payables.')->middleware('role:super_admin,shop_owner')->group(function () {
        Route::post('/', [PayableController::class, 'store'])->name('store');
        Route::post('/{payable}/payment', [PayableController::class, 'recordPayment'])->name('payment');
        Route::delete('/{payable}', [PayableController::class, 'destroy'])->name('destroy');
    });

    // ---------- মালিকের প্রফিট উইথড্রয়াল (টাকা তোলার হিসাব): শুধু Super Admin ও Shop Owner ----------
    Route::prefix('profit-withdrawals')->name('profit-withdrawals.')->middleware('role:super_admin,shop_owner')->group(function () {
        Route::post('/', [ProfitWithdrawalController::class, 'store'])->name('store');
        Route::delete('/{profitWithdrawal}', [ProfitWithdrawalController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('receivables')->name('receivables.')->middleware('role:super_admin,shop_owner')->group(function () {
        Route::post('/', [ReceivableController::class, 'store'])->name('store');
        Route::post('/{receivable}/payment', [ReceivableController::class, 'recordPayment'])->name('payment');
        Route::delete('/{receivable}', [ReceivableController::class, 'destroy'])->name('destroy');
    });

    // ---------- সেল/POS: 'sales' পারমিশন লাগবে ----------
    Route::prefix('sales')->name('sales.')->middleware('shop.permission:sales')->group(function () {
        Route::get('/', [SaleController::class, 'index'])->name('index');
        Route::get('/create', [SaleController::class, 'create'])->name('create');
        Route::post('/', [SaleController::class, 'store'])->name('store');
        Route::get('/{sale}', [SaleController::class, 'show'])->name('show');
    });

    // ---------- প্রোডাক্ট রিটার্ন/রিফান্ড: 'sales' পারমিশন লাগবে ----------
    Route::prefix('returns')->name('returns.')->middleware('shop.permission:sales')->group(function () {
        Route::get('/', [ReturnController::class, 'index'])->name('index');
        Route::post('/', [ReturnController::class, 'store'])->name('store');
    });

    // ---------- সার্ভিস ফি: 'service_fee' পারমিশন লাগবে ----------
    Route::prefix('service-fees')->name('service-fees.')->middleware('shop.permission:service_fee')->group(function () {
        Route::get('/', [ServiceFeeController::class, 'index'])->name('index');
        Route::post('/', [ServiceFeeController::class, 'store'])->name('store');
        Route::delete('/{serviceFee}', [ServiceFeeController::class, 'destroy'])->name('destroy');
    });

    // ---------- খরচ: 'expenses' পারমিশন লাগবে ----------
    Route::prefix('expenses')->name('expenses.')->middleware('shop.permission:expenses')->group(function () {
        Route::get('/', [ExpenseController::class, 'index'])->name('index');
        Route::post('/', [ExpenseController::class, 'store'])->name('store');
        Route::delete('/{expense}', [ExpenseController::class, 'destroy'])->name('destroy');
    });

    // ---------- অ্যাক্টিভিটি লগ: শুধু Super Admin ও Shop Owner দেখতে পারবে ----------
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])
        ->name('activity-logs.index')->middleware('role:super_admin,shop_owner');

    // ---------- প্রোডাক্ট বাল্ক ইমপোর্ট: 'products' পারমিশন লাগবে ----------
    Route::prefix('products-import')->name('products.import.')->middleware('shop.permission:products')->group(function () {
        Route::get('/', [ProductImportController::class, 'create'])->name('form');
        Route::post('/', [ProductImportController::class, 'store'])->name('store');
        Route::get('/sample', [ProductImportController::class, 'sample'])->name('sample');
    });

    // ---------- রিপোর্ট: 'reports' পারমিশন লাগবে ----------
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index')
        ->middleware('shop.permission:reports');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export')
        ->middleware('shop.permission:reports');
});
