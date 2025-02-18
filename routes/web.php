<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Chỉ Admin mới có quyền vào Dashboard


// Nhóm các route của Admin vào cùng 1 middleware
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('payment_methods', PaymentMethodController::class);
    Route::resource('categories', CategoryController::class)->names('admin.categories'); // 👈 THÊM NÀY
});


