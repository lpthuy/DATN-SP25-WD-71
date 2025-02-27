<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\client\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Auth::routes();

<<<<<<< Updated upstream
Route::get('/', function(){
    return view('clients.index');
});
=======
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
>>>>>>> Stashed changes

Route::get('/gioi-thieu', [App\Http\Controllers\HomeController::class, 'about'])->name('about');

Route::get('/san-pham', [App\Http\Controllers\HomeController::class, 'product'])->name('product');

Route::get('/chi-tiet-san-pham', [App\Http\Controllers\HomeController::class, 'productDetail'])->name('productDetail');

Route::get('/danh-muc', [App\Http\Controllers\HomeController::class, 'productbycategory'])->name('productbycategory');

Route::get('/tin-tuc', [App\Http\Controllers\HomeController::class, 'post'])->name('post');

Route::get('/lien-he', [App\Http\Controllers\HomeController::class, 'contact'])->name('contact');

Route::get('/tim-kiem', [App\Http\Controllers\HomeController::class, 'search'])->name('search');

Route::get('/yeu-thich', [App\Http\Controllers\HomeController::class, 'wishlist'])->name('wishlist');

Route::get('/gio-hang', [App\Http\Controllers\HomeController::class, 'cart'])->name('cart');

Route::get('/kiem-tra-don-hang', [App\Http\Controllers\HomeController::class, 'checkOrder'])->name('checkOrder');

Route::get('/chinh-sach-giao-hang', [App\Http\Controllers\HomeController::class, 'chinhSachGiaoHang'])->name('chinhSachGiaoHang');

Route::get('/dang-nhap', [App\Http\Controllers\HomeController::class, 'login'])->name('login');

Route::get('/dang-ky', [App\Http\Controllers\HomeController::class, 'register'])->name('register');

Route::get('/doi-mat-khau', [App\Http\Controllers\HomeController::class, 'changePassword'])->name('changePassword');

Route::get('/tai-khoan', [App\Http\Controllers\HomeController::class, 'profile'])->name('profile');

Route::get('/don-hang', [App\Http\Controllers\HomeController::class, 'order'])->name('order');

Route::get('/dia-chi', [App\Http\Controllers\HomeController::class, 'address'])->name('address');
// Chỉ Admin mới có quyền vào Dashboard


// Nhóm các route của Admin vào cùng 1 middleware
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('payment_methods', PaymentMethodController::class);
<<<<<<< Updated upstream


    
=======
    Route::resource('categories', CategoryController::class)->names('admin.categories'); // 👈 THÊM NÀY
    Route::resource('banners', BannerController::class)->names('admin.banners');
>>>>>>> Stashed changes
});

