<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaymentMethodController;

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\SizeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/gioi-thieu', [HomeController::class, 'about'])->name('about');
Route::get('/san-pham', [HomeController::class, 'product'])->name('product');
Route::get('/chi-tiet-san-pham', [HomeController::class, 'productDetail'])->name('productDetail');
Route::get('/danh-muc', [HomeController::class, 'productbycategory'])->name('productbycategory');
Route::get('/tin-tuc', [HomeController::class, 'post'])->name('post');
Route::get('/lien-he', [HomeController::class, 'contact'])->name('contact');
Route::get('/tim-kiem', [HomeController::class, 'search'])->name('search');
Route::get('/yeu-thich', [HomeController::class, 'wishlist'])->name('wishlist');
Route::get('/gio-hang', [HomeController::class, 'cart'])->name('cart');
Route::get('/kiem-tra-don-hang', [HomeController::class, 'checkOrder'])->name('checkOrder');
Route::get('/chinh-sach-giao-hang', [HomeController::class, 'chinhSachGiaoHang'])->name('chinhSachGiaoHang');
Route::get('/dang-nhap', [HomeController::class, 'login'])->name('login');
Route::get('/dang-ky', [HomeController::class, 'register'])->name('register');
Route::get('/doi-mat-khau', [HomeController::class, 'changePassword'])->name('changePassword');
Route::get('/tai-khoan', [HomeController::class, 'profile'])->name('profile');
Route::get('/don-hang', [HomeController::class, 'order'])->name('order');
Route::get('/dia-chi', [HomeController::class, 'address'])->name('address');

// Chỉ Admin mới có quyền vào Dashboard

// Nhóm các route của Admin vào cùng 1 middleware
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('payment_methods', PaymentMethodController::class);
    Route::resource('banners', BannerController::class)->names('admin.banners');

    // Quản lý danh mục sản phẩm
    Route::resource('categories', CategoryController::class);
// Quản lý sản phẩm
Route::resource('products', ProductController::class);
// Quản lý kích thước sản phẩm (Size)
Route::resource('sizes', SizeController::class);
// Quản lý màu sắc sản phẩm (Color)
Route::resource('colors', ColorController::class);
// Quản lý biến thể sản phẩm (Product Variants)
Route::resource('products_variants', ProductVariantController::class);
 // Quản lý hình ảnh sản phẩm (Product Images)
 Route::resource('products_images', ProductImageController::class);
    
});
