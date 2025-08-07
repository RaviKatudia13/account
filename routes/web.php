<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\InternalTransferController;
use Laravel\Fortify\Features;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return redirect()->route('login'); 
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Users/Clients routes
    Route::get('/users', [\App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users', [\App\Http\Controllers\Admin\AdminUserController::class, 'store'])->name('users.store');
    Route::patch('/users/{client}', [\App\Http\Controllers\Admin\AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [\App\Http\Controllers\Admin\AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/create', [\App\Http\Controllers\Admin\AdminUserController::class, 'create'])->name('users.create');
    
    // Categories routes
    Route::get('/categories', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{id}', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/categories/{id}/edit', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'update'])->name('categories.update');

    // Invoice routes
    Route::get('/invoices', [\App\Http\Controllers\Admin\AdminInvoiceController::class, 'index'])->name('invoices.index');
    Route::post('/invoices', [\App\Http\Controllers\Admin\AdminInvoiceController::class, 'store'])->name('invoices.store');
    Route::post('/invoices/{invoice}/pay', [\App\Http\Controllers\Admin\AdminInvoiceController::class, 'pay'])->name('invoices.pay');
    // Payments route
    Route::get('/payments', [\App\Http\Controllers\Admin\AdminInvoiceController::class, 'payments'])->name('payments.index');
    Route::get('/invoices/create', [\App\Http\Controllers\Admin\AdminInvoiceController::class, 'create'])->name('invoices.create');
    
    // Settings route
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('settings');
    
    // Reports route
    Route::get('/reports', function () {
        return view('admin.reports');
    })->name('reports');
    
    // Analytics route
    Route::get('/analytics', function () {
        return view('admin.analytics');
    })->name('analytics');

    // Payment Methods route
    Route::resource('payment-methods', \App\Http\Controllers\Admin\AdminPaymentMethodController::class)->names('payment_methods');

    // Accounts route
    Route::resource('accounts', \App\Http\Controllers\Admin\AccountController::class);

    // Vendor routes
    Route::resource('vendors', App\Http\Controllers\Admin\VendorController::class);

    // Vendor Categories route
    Route::resource('vendor-categories', App\Http\Controllers\Admin\VendorCategoryController::class);

    // Employee routes
    Route::resource('employees', App\Http\Controllers\Admin\EmployeeController::class);

    // Employee Categories route
    Route::resource('employee-categories', App\Http\Controllers\Admin\EmployeeCategoryController::class);

    // Income routes
    Route::resource('income', App\Http\Controllers\Admin\IncomeController::class);

    // Expense routes
    Route::resource('expense', App\Http\Controllers\Admin\ExpenseController::class);

    // Income/Expense Categories route
    Route::resource('inc-exp-category', App\Http\Controllers\Admin\IncExpCategoryController::class)->names('inc-exp-category');

    // Expense payment routes
    Route::get('/expenses/{id}/payment', [\App\Http\Controllers\Admin\ExpenseController::class, 'showPaymentModal'])->name('expense.payment.modal');
    Route::post('/expenses/{id}/payment', [\App\Http\Controllers\Admin\ExpenseController::class, 'storePayment'])->name('expense.payment.store');

    // AJAX endpoint for remaining amount in Add Due modal
    Route::get('/expense-remaining', [\App\Http\Controllers\Admin\ExpenseController::class, 'getRemainingAmount'])->name('expense.remaining');

    Route::get('/manage-due', [\App\Http\Controllers\Admin\VendorController::class, 'vendorDueIndex'])->name('manage-due');

    Route::get('/employee-due', [\App\Http\Controllers\Admin\EmployeeController::class, 'employeeDueIndex'])->name('employee-due');

    Route::post('/employee-due', [\App\Http\Controllers\Admin\EmployeeController::class, 'storeEmployeeDue'])->name('employee-due.store');

    Route::post('/vendor-due', [\App\Http\Controllers\Admin\VendorController::class, 'storeVendorDue'])->name('vendor-due.store');

    Route::post('/vendor-due/{due}/pay', [\App\Http\Controllers\Admin\VendorController::class, 'payVendorDue'])->name('vendor-due.pay');

    Route::post('/employee-due/{due}/pay', [\App\Http\Controllers\Admin\EmployeeController::class, 'payEmployeeDue'])->name('employee-due.pay');

    // Internal Transfer List route
    Route::get('/internal-transfer/list', [InternalTransferController::class, 'index'])->name('internal-transfer.list');
    Route::get('/internal-transfer/add', [InternalTransferController::class, 'create'])->name('internal-transfer.add');
    Route::post('/internal-transfer/add', [InternalTransferController::class, 'store'])->name('internal-transfer.store');

    // Subscription Client routes
    Route::resource('subscription-clients', App\Http\Controllers\Admin\SubscriptionClientListController::class);
    Route::resource('subscription-client-payments', App\Http\Controllers\Admin\SubscriptionClientPaymentController::class);
    Route::post('subscription-client-payments/{payment}/record-payment', [App\Http\Controllers\Admin\SubscriptionClientPaymentController::class, 'recordPayment'])->name('subscription-client-payments.record-payment');

    Route::post('/vendor/{vendor}/payments', [\App\Http\Controllers\Admin\VendorController::class, 'storePayment'])->name('vendor.payments.store');

    Route::post('/employee/{employee}/payments', [\App\Http\Controllers\Admin\EmployeeController::class, 'storePayment'])->name('employee.payments.store');
});

Route::get('/otp', [AuthenticatedSessionController::class, 'showOtpForm'])->name('auth.otp.form')->middleware('guest');
Route::post('/otp', [AuthenticatedSessionController::class, 'verifyOtp'])->name('auth.otp.verify')->middleware('guest');
Route::post('/otp/resend', [AuthenticatedSessionController::class, 'resendOtp'])->name('auth.otp.resend')->middleware('guest');

// Secure 2FA routes with password.confirm middleware
Route::post('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'store'])->middleware(['auth', 'password.confirm']);
Route::delete('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'destroy'])->middleware(['auth', 'password.confirm']);

require __DIR__.'/auth.php';
