<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartDetailController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\RamController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\adminCatCategoriesController;
use App\Http\Controllers\Client\CartController as ClientCartController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\MomoController;
use App\Http\Controllers\CustomersControllerr;

use App\Http\Controllers\Client\ProductClientController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\ProductVariantController as ClientProductVariantController;
use App\Http\Controllers\Client\UserProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductVariantImageController;

// Trang mặc định → login admin
Route::get('/', function () {
    return view('admin.auth.login');
});

// Trang người dùng (client)
Route::get('/home', [ProductClientController::class, 'index'])->name('home');
Route::get('/product/{id}', [ProductClientController::class, 'show'])->name('product.show');

// Đăng nhập / đăng ký dùng chung
Route::get('/login', [AccountController::class, 'showLoginForm'])->name('taikhoan.showLoginForm');
Route::post('/login', [AccountController::class, 'login'])->name('taikhoan.login');
Route::post('/register', [AccountController::class, 'register'])->name('taikhoan.register');

// 🌟 Các chức năng yêu cầu đăng nhập
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AccountController::class, 'logout'])->name('taikhoan.logout');
    Route::post('/buy-now', [ClientCartController::class, 'buyNow'])->name('cart.buyNow');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    // Trang người dùng: dashboard, profile, đơn hàng
    Route::get('/user/dashboard', function () {
        return view('client.user.dashboard');
    })->name('user.dashboard');
    Route::get('/user/profile', function () {
        return view('client.user.profile');
    })->name('user.profile');
    Route::get('/user/orders', function () {
        return view('client.user.orders');
    })->name('user.orders');
    //
    Route::get('/user/profile', [UserProfileController::class, 'show'])->name('user.profile');
    Route::get('/user/profile/edit', [UserProfileController::class, 'edit'])->name('user.profile.edit');
    Route::post('/user/profile/update', [UserProfileController::class, 'update'])->name('user.profile.update');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    //
    Route::post('/cart/add', [ClientCartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [ClientCartController::class, 'show'])->name('cart.show');

Route::get('/generate-momo-qr', [MomoController::class, 'generateQR'])->name('momo.qr');
});

// Khu vực quản trị (admin)
Route::prefix('admin')->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('variants', ProductVariantController::class);
    Route::resource('promotions', PromotionController::class);
    Route::resource('rams', RamController::class);
    Route::resource('storages', StorageController::class);
    Route::resource('colors', ColorController::class);
    Route::resource('customers', CustomersControllerr::class);
    Route::resource('accounts', AccountController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('carts', CartController::class)->only(['index', 'show', 'destroy']);
    Route::resource('cart-details', CartDetailController::class);
    Route::delete('/admin/cart-details/{id}', [CartDetailController::class, 'destroy'])->name('cart-details.destroy');

    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::put('/orders/{id}', [OrderController::class, 'update'])->name('admin.orders.update');
    Route::get('/admin/orders/place/{cartId}', [OrderController::class, 'placeOrderFromCart'])->name('admin.orders.place');
     Route::post('/variants/{id}/images', [ProductVariantImageController::class, 'storeImages'])->name('admin.variant.images.store');
    Route::delete('/variant-images/{id}', [ProductVariantImageController::class, 'deleteImage'])->name('admin.variant.images.delete');
});
