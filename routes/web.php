<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\User\CashierController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Redirect after login based on role
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('cashier.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth'])->group(function () {
    Route::middleware([CheckRole::class . ':admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
        
        // Reports route with feature check
        if (config('features.financial_reports')) {
            Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        }
    });
});

// User/Kasir Routes
Route::middleware(['auth'])->group(function () {
    Route::middleware([CheckRole::class . ':user'])->prefix('cashier')->name('cashier.')->group(function () {
        Route::get('/', [CashierController::class, 'index'])->name('index');
        Route::post('/add-to-cart', [CashierController::class, 'addToCart'])->name('add-to-cart');
        Route::post('/update-cart', [CashierController::class, 'updateCart'])->name('update-cart');
        Route::post('/remove-from-cart', [CashierController::class, 'removeFromCart'])->name('remove-from-cart');
        Route::post('/checkout', [CashierController::class, 'checkout'])->name('checkout');
        Route::get('/invoice/{transaction}', [CashierController::class, 'invoice'])->name('invoice');
        Route::get('/transactions', [CashierController::class, 'transactions'])->name('transactions');
        Route::get('/transactions/{transaction}', [CashierController::class, 'showTransaction'])->name('transactions.show');
    });
});

require __DIR__.'/auth.php';
