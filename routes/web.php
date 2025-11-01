<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Director\DashboardController as DirectorDashboardController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        // User is logged in, redirect to appropriate dashboard
        $user = Auth::user();
        if ($user->isDirector()) {
            return redirect()->route('director.dashboard');
        } elseif ($user->isManager()) {
            return redirect()->route('manager.dashboard');
        } else { // Employee or other roles
            return redirect()->route('employee.dashboard');
        }
    } else {
        // User is not logged in, redirect to login page
        return redirect()->route('login');
    }
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
    Route::get('password/reset', function () {
        return 'Fitur lupa password belum tersedia.';
    })->name('password.request');
});

// CSRF Token refresh route
Route::get('csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
})->middleware('web');

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('logout', [LoginController::class, 'logout'])->name('logout.get');

    // Director Routes
    Route::prefix('director')->name('director.')->middleware(['auth', \App\Http\Middleware\CheckRole::class . ':director'])->group(function () {
        Route::get('/dashboard', [DirectorDashboardController::class, 'index'])->name('dashboard');

        Route::resource('users', \App\Http\Controllers\Director\UserController::class);
        Route::resource('branches', \App\Http\Controllers\Director\BranchController::class);

        Route::get('reports/keuangan', [\App\Http\Controllers\Reports\ReportController::class, 'keuangan'])->name('reports.keuangan');
        Route::get('reports/integrated', [\App\Http\Controllers\Reports\ReportController::class, 'integratedReport'])->name('reports.integrated');

        // Export routes
        Route::get('export/users', [\App\Http\Controllers\ExportController::class, 'exportUsers'])->name('export.users');
        Route::get('export/branches', [\App\Http\Controllers\ExportController::class, 'exportBranches'])->name('export.branches');
        Route::get('export/transactions', [\App\Http\Controllers\ExportController::class, 'exportTransactions'])->name('export.transactions');

        // Route for audit log removed as part of audit log feature removal
        // Route::get('reports/audit-log', [\App\Http\Controllers\Director\ReportController::class, 'auditLog'])->name('reports.auditLog');
    });

    // Manager Routes
    Route::prefix('manager')->name('manager.')->middleware(['auth', \App\Http\Middleware\CheckRole::class . ':manager'])->group(function () {
        Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');

        // Product Management
        Route::resource('products', \App\Http\Controllers\Manager\ProductController::class);

        // Category Management
        Route::resource('categories', \App\Http\Controllers\Manager\CategoryController::class);

        // Employee Management
        Route::resource('employees', \App\Http\Controllers\Manager\EmployeeController::class);

        // Transaction Management for Manager
        Route::get('transactions', [\App\Http\Controllers\Manager\TransactionController::class, 'index'])->name('transactions.index');
        Route::get('transactions/{transaction}', [\App\Http\Controllers\Manager\TransactionController::class, 'show'])->name('transactions.show');

        // Return Management for Manager
        Route::get('returns', [\App\Http\Controllers\Manager\ReturnController::class, 'index'])->name('returns.index');
        Route::get('returns/{return}', [\App\Http\Controllers\Manager\ReturnController::class, 'show'])->name('returns.show');
        Route::post('returns/{return}/approve', [\App\Http\Controllers\Manager\ReturnController::class, 'approve'])->name('returns.approve');
        Route::post('returns/{return}/reject', [\App\Http\Controllers\Manager\ReturnController::class, 'reject'])->name('returns.reject');

        // Damaged Stock Management for Manager
        Route::get('damaged-stock', [\App\Http\Controllers\Manager\DamagedStockController::class, 'index'])->name('damaged-stock.index');
        Route::get('damaged-stock/{damagedStock}', [\App\Http\Controllers\Manager\DamagedStockController::class, 'show'])->name('damaged-stock.show');
        Route::post('damaged-stock/{damagedStock}/action', [\App\Http\Controllers\Manager\DamagedStockController::class, 'takeAction'])->name('damaged-stock.action');

        // Reports
        Route::get('reports/keuangan', [\App\Http\Controllers\Reports\ReportController::class, 'keuangan'])->name('reports.keuangan');
        Route::get('reports/integrated', [\App\Http\Controllers\Reports\ReportController::class, 'integratedReport'])->name('reports.integrated');

        // Export routes
        Route::get('export/products', [\App\Http\Controllers\ExportController::class, 'exportProducts'])->name('export.products');
        Route::get('export/transactions', [\App\Http\Controllers\ExportController::class, 'exportTransactions'])->name('export.transactions');

        // Manager Chat routes removed as chat feature is removed
    });

    // Employee Routes
    Route::prefix('employee')->name('employee.')->middleware(['auth', \App\Http\Middleware\CheckRole::class . ':employee'])->group(function () {
        Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');

        Route::get('transactions/create', [\App\Http\Controllers\Employee\TransactionController::class, 'create'])->name('transactions.create');
        Route::post('transactions', [\App\Http\Controllers\Employee\TransactionController::class, 'store'])->name('transactions.store');
        Route::get('transactions/{transaction}', [\App\Http\Controllers\Employee\TransactionController::class, 'show'])->name('transactions.show');

        Route::get('returns', [\App\Http\Controllers\Employee\ReturnController::class, 'index'])->name('returns.index');
        Route::get('returns/create', [\App\Http\Controllers\Employee\ReturnController::class, 'create'])->name('returns.create');
        Route::post('returns', [\App\Http\Controllers\Employee\ReturnController::class, 'store'])->name('returns.store');
        Route::get('returns/{return}', [\App\Http\Controllers\Employee\ReturnController::class, 'show'])->name('returns.show');

        Route::get('stocks', [\App\Http\Controllers\Employee\StockController::class, 'index'])->name('stocks.index');

        // Invoice generation for employees
        Route::get('transactions/{transaction}/invoice', [\App\Http\Controllers\Employee\TransactionController::class, 'generateInvoice'])->name('transactions.invoice');

        // Employee Reports
        Route::get('reports/integrated', [\App\Http\Controllers\Reports\ReportController::class, 'integratedReport'])->name('reports.integrated');
        Route::get('transactions', [\App\Http\Controllers\Employee\TransactionController::class, 'index'])->name('transactions.index');

        // Export routes
        Route::get('export/transactions', [\App\Http\Controllers\ExportController::class, 'exportTransactions'])->name('export.transactions');

        // Employee Chat routes removed as chat feature is removed
    });
});
