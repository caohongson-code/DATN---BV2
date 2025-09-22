<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CommentAdminController;
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
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Client\CartController as ClientCartController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\MomoController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\CustomersControllerr;

use App\Http\Controllers\Client\ProductClientController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\ProductVariantController as ClientProductVariantController;
use App\Http\Controllers\Client\ReviewController;
use App\Http\Controllers\Client\UserProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductVariantImageController;
use App\Http\Controllers\DashboardControlle;
use App\Http\Controllers\Client\CategoryClientController;
use App\Http\Controllers\Client\ChatbotController as ClientChatbotController;
use App\Http\Controllers\Client\CommentController;
use App\Http\Controllers\Client\ContactController;
use App\Http\Controllers\Client\PromotionController as ClientPromotionController;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\Client\NewsClientController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\Client\IntroduceController;
use App\Http\Controllers\ContactController as ControllersContactController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RolePermissionController;

// Trang mặc định → login admin
Route::get('/', function () {
    return redirect()->route('home');
});

// Trang người dùng (client)
Route::get('/home', [ProductClientController::class, 'index'])->name('home');
Route::get('/product/{id}', [ProductClientController::class, 'show'])->name('product.show');
Route::get('/categories', [CategoryClientController::class, 'index'])->name('client.categories');
Route::get('/categories/{id}', [CategoryClientController::class, 'index'])->name('client.categories.filter');
Route::get('/search', [App\Http\Controllers\Client\ProductClientController::class, 'search'])->name('home.search');
Route::get('/search', [\App\Http\Controllers\Client\ProductClientController::class, 'search'])->name('client.search');

// News routes (Client)
Route::get('/news', [NewsClientController::class, 'index'])->name('client.news.index');
Route::get('/news/{slug}', [NewsClientController::class, 'show'])->name('client.news.show');
Route::get('/introduce', [IntroduceController::class, 'index'])->name('client.introduce');
// Đăng nhập / đăng ký dùng chung
Route::get('/login', [AccountController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AccountController::class, 'login'])->name('taikhoan.login');
Route::post('/register', [AccountController::class, 'register'])->name('taikhoan.register');

    Route::get('forgot-password', [AccountController::class, 'showForgotForm'])->name('password.request');
    Route::post('forgot-password', [AccountController::class, 'sendResetLink'])->name('password.email');
    Route::get('reset-password/{token}', [AccountController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [AccountController::class, 'resetPassword'])->name('password.update');

    Route::get('/products', [ProductClientController::class, 'index'])->name('product.all');
// 🌟 Các chức năng yêu cầu đăng nhập
Route::middleware('auth','prevent.admin.order')->group(function () {
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
    // Notifiations
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::get('/user/profile', [UserProfileController::class, 'show'])->name('user.profile');
    Route::post('/user/profile/update', [UserProfileController::class, 'update'])->name('user.profile.update');
    //
    Route::get('/user/orders', [ClientOrderController::class, 'show'])->name('user.orders');
    Route::get('/user/orders/{id}', [ClientOrderController::class, 'detail'])->name('user.orders.detail');
    Route::post('/client/orders/{id}/cancel', [ClientOrderController::class, 'ajaxCancel'])->name('client.orders.cancel');
    Route::post('/orders/return-refund/{id}', [ClientOrderController::class, 'requestReturnRefund'])->name('orders.return_refund');
    Route::post('/return-request/{id}/cancel', [ClientOrderController::class, 'cancelReturnRequest'])->name('return.cancel');
    Route::post('/orders/{id}/confirm-received', [ClientOrderController::class, 'confirmReceived'])->name('orders.confirm_received');
    Route::post('/orders/return/{id}/submit-tracking', [ClientOrderController::class, 'submitTrackingCode'])->name('user.return.submit_tracking');
    Route::get('/orders/return/{id}/enter-tracking', [ClientOrderController::class, 'showTrackingForm'])->name('user.return.enter_tracking');



    Route::post('/orders/report-issue', [ClientOrderController::class, 'reportDeliveryIssue']);

    Route::post('/client/reviews', [ReviewController::class, 'store'])->name('client.reviews.store');


    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');


    // Giỏ hàng
    Route::post('/cart/add', [ClientCartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [ClientCartController::class, 'show'])->name('cart.show');
    Route::delete('/cart/{id}', [ClientCartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update-before-checkout', [ClientCartController::class, 'updateBeforeCheckout'])->name('cart.updateBeforeCheckout');
    Route::patch('/cart/update-quantity/{id}', [ClientCartController::class, 'updateQuantity'])->name('cart.updateQuantity');

    // Gửi yêu cầu thanh toán lên MoMo
    Route::post('/momo_payment', [MomoController::class, 'momo_payment'])->name('momo.payment');

    // IPN từ server MoMo gửi về (POST), nơi xử lý đơn hàng chính thức
    Route::post('/momo_ipn', [MomoController::class, 'handleMomoIpn'])->name('momo.ipn');

    // Người dùng quay lại sau khi thanh toán xong, chỉ hiển thị kết quả
    // Route::get('/momo_redirect/{orderId}', [MomoController::class, 'handleMomoRedirect'])->name('momo.redirect');
    Route::get('/momo/result', [CheckoutController::class, 'momoResult'])->name('momo.result');

    // Hiển thị tất cả sản phẩm (client)

    // IPN từ server MoMo gửi về (POST), nơi xử lý đơn hàng chính thức
    Route::post('/momo_ipn', [MomoController::class, 'handleMomoIpn'])->name('momo.ipn');
    // Người dùng quay lại sau khi thanh toán xong, chỉ hiển thị kết quả
    Route::get('/momo_redirect', [MomoController::class, 'handleMomoRedirect'])->name('momo.redirect');
    Route::post('/momo/retry/{orderId}', [MomoController::class, 'retryPayment'])->name('client.momo.retry');
    Route::post('/client/orders/{id}/convert-to-cod', [MomoController::class, 'convertToCod'])->name('client.momo.to_cod');

    // Trang ví người dùng
    //phần khuyến mại
    Route::get('/khuyen-mai', [ClientPromotionController::class, 'index'])
        ->middleware('auth')
        ->name('client.promotions.index');
    Route::get('/khuyen-mai/luu/{id}', [ClientPromotionController::class, 'save'])
        ->middleware('auth')
        ->name('client.promotions.save');
    Route::get('/khuyen-mai/bo-luu/{id}', [ClientPromotionController::class, 'unsave'])
        ->middleware('auth')
        ->name('client.promotions.unsave');

    //binh luan
    Route::post('/client/comments', [CommentController::class, 'store'])->name('client.comments.store');

    //
    Route::get('/lien-he', [ContactController::class, 'showContactForm'])->name('client.contact');
Route::post('/lien-he', [ContactController::class, 'submitContactForm'])->name('client.contact.submit');
});
Route::post('/chatbot/send', [ChatbotController::class, 'send']);
// Khu vực quản trị (admin)
Route::prefix('admin')->middleware(['auth', CheckRole::class . ':1'])->group(function () {
    Route::get('/dashboard', [DashboardControlle::class, 'index'])->name('admin.dashboard');

    Route::resource('accounts', AccountController::class);
    Route::resource('roles', RoleController::class);
    Route::get('accounts/show', [AccountController::class, 'show'])->name('admin.profile');
    Route::post('accounts/update-profile', [AccountController::class, 'updateAdminProfile'])->name('admin.updateProfile');
    Route::post('accounts/update-password', [AccountController::class, 'updateAdminPassword'])->name('admin.updatePassword');
});
// 🌟 Admin + Quản trị viên phụ (role_id = 1,2)
// Route::prefix('admin')->middleware(['auth', CheckRole::class . ':1,2'])->group(function () {
//     Route::resource('products', ProductController::class);
//     Route::resource('categories', CategoryController::class);
//     Route::resource('variants', ProductVariantController::class);
//     Route::resource('promotions', PromotionController::class);
//     Route::resource('rams', RamController::class);
//     Route::resource('storages', StorageController::class);
//     Route::resource('colors', ColorController::class);
//     Route::resource('customers', CustomersControllerr::class);
//     Route::resource('carts', CartController::class)->only(['index', 'show', 'destroy']);
//     Route::resource('cart-details', CartDetailController::class);
//     Route::delete('/cart-details/{id}', [CartDetailController::class, 'destroy'])->name('cart-details.destroy');

//     // News
//     Route::resource('news', NewsController::class);
//     Route::post('/news/{id}/toggle-featured', [NewsController::class, 'toggleFeatured'])->name('admin.news.toggle-featured');
//     Route::post('/news/{id}/toggle-hot', [NewsController::class, 'toggleHot'])->name('admin.news.toggle-hot');
//     Route::post('/news/bulk-action', [NewsController::class, 'bulkAction'])->name('admin.news.bulk-action');
//     Route::post('/news/test-upload', [NewsController::class, 'testUpload'])->name('admin.news.test-upload');

//     // Orders
//     Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
//     Route::get('/orders/{id}', [OrderController::class, 'show'])->name('admin.orders.show');
//     Route::put('/orders/{id}', [OrderController::class, 'update'])->name('admin.orders.update');
//     Route::get('/orders/place/{cartId}', [OrderController::class, 'placeOrderFromCart'])->name('admin.orders.place');
//     Route::post('/variants/{id}/images', [ProductVariantImageController::class, 'storeImages'])->name('admin.variant.images.store');
//     Route::delete('/variant-images/{id}', [ProductVariantImageController::class, 'deleteImage'])->name('admin.variant.images.delete');

//     // Return requests
//     Route::get('return-requests', [OrderController::class, 'listReturnRequests'])->name('admin.return_requests.index');
//     Route::get('return-requests/{id}/approve', [OrderController::class, 'approveReturnRequest'])->name('admin.return_requests.approve');
//     Route::get('return-requests/{id}/reject', [OrderController::class, 'rejectReturnRequest'])->name('admin.return_requests.reject');
//     Route::post('/orders/returns/{id}/progress', [OrderController::class, 'updateReturnProgress'])->name('admin.orders.progress');
//     Route::get('/orders/returns/{id}/refund-form', [OrderController::class, 'showRefundForm'])->name('admin.orders.refund_form');
//     Route::post('/orders/returns/{id}/process-refund', [OrderController::class, 'processRefund'])->name('admin.orders.process_refund');
//     Route::get('/admin/orders/{id}/refund-detail', [OrderController::class, 'refundDetail'])->name('admin.orders.refund_detail');
//     // Comments
//     Route::get('comments', [CommentSController::class, 'index'])->name('comments.index');
//     Route::get('/hide/{id}', [CommentSController::class, 'hide'])->name('comments.hide');
//     Route::get('/show/{id}', [CommentSController::class, 'showComment'])->name('comments.showComment');
//     //
//     Route::get('/admin/contact', [ControllersContactController::class, 'index'])->name('admin.contact.index');
// Route::get('/admin/contact/{id}', [ControllersContactController::class, 'show'])->name('admin.contact.show');
// Route::put('/admin/contact/{id}/status', [ControllersContactController::class, 'updateStatus'])->name('admin.contact.status');
// Route::delete('/admin/contact/{id}', [ControllersContactController::class, 'destroy'])->name('admin.contact.destroy');
// });
Route::prefix('admin')->group(function () {


    Route::get('/dashboard', [DashboardControlle::class, 'index'])
        ->middleware('check.permission:pos-sell')
        ->name('admin.dashboard');

    // Products
    Route::resource('products', ProductController::class)
        ->middleware('check.permission:product-manage')
        ->names('products');
    Route::resource('categories', CategoryController::class)
        ->middleware('check.permission:category-manage')
        ->names('categories');
    Route::resource('variants', ProductVariantController::class)
        ->middleware('check.permission:product-attribute')
        ->names('variants');
    Route::resource('rams', RamController::class)
        ->middleware('check.permission:product-attribute')
        ->names('rams');
    Route::resource('storages', StorageController::class)
        ->middleware('check.permission:product-attribute')
        ->names('storages');
    Route::resource('colors', ColorController::class)
        ->middleware('check.permission:product-attribute')
        ->names('colors');

    // Customers
    Route::resource('customers', CustomersControllerr::class)
        ->middleware('check.permission:customer-manage')
        ->names('customers');
        Route::resource('accounts', AccountController::class)
        ->middleware('check.permission:staff-manage')
        ->names('accounts')
        ->except(['show']); // loại bỏ route show

        Route::get('accounts/show', [AccountController::class, 'show'])->middleware('check.permission:profile-view')->name('admin.profile');

        Route::post('accounts/update-profile', [AccountController::class, 'updateAdminProfile'])
        ->middleware('check.permission:profile-view')->name('admin.updateProfile');
        Route::post('accounts/update-password', [AccountController::class, 'updateAdminPassword'])
        ->middleware('check.permission:profile-view')->name('admin.updatePassword');
    // Roles
    Route::resource('roles', RoleController::class)
        ->middleware('check.permission:role-manage')
                        ->names('roles');
    Route::resource('promotions', PromotionController::class)
    ->middleware('check.permission:promotion-manage')
    ->names('promotions');

                // Orders
                Route::get('/orders', [OrderController::class, 'index'])
                    ->middleware('check.permission:order-manage')
                    ->name('admin.orders.index');

                Route::get('/orders/{id}', [OrderController::class, 'show'])
                    ->middleware('check.permission:order-manage')
                    ->name('admin.orders.show');

                Route::put('/orders/{id}', [OrderController::class, 'update'])
                    ->middleware('check.permission:order-manage')
                    ->name('admin.orders.update');

// Return requests
    Route::get('return-requests', [OrderController::class, 'listReturnRequests'])->name('admin.return_requests.index');
    Route::get('return-requests/{id}/approve', [OrderController::class, 'approveReturnRequest'])->name('admin.return_requests.approve');
    Route::get('return-requests/{id}/reject', [OrderController::class, 'rejectReturnRequest'])->name('admin.return_requests.reject');
    Route::post('/orders/returns/{id}/progress', [OrderController::class, 'updateReturnProgress'])->name('admin.orders.progress');
    Route::get('/orders/returns/{id}/refund-form', [OrderController::class, 'showRefundForm'])->name('admin.orders.refund_form');
    Route::post('/orders/returns/{id}/process-refund', [OrderController::class, 'processRefund'])->name('admin.orders.process_refund');
    Route::get('/admin/orders/{id}/refund-detail', [OrderController::class, 'refundDetail'])->name('admin.orders.refund_detail');
                Route::resource('news', NewsController::class)
                ->middleware('check.permission:news-manage');
                Route::post('/news/{id}/toggle-featured', [NewsController::class, 'toggleFeatured'])->name('admin.news.toggle-featured')
                ->middleware('check.permission:news-manage');
                Route::post('/news/{id}/toggle-hot', [NewsController::class, 'toggleHot'])->name('admin.news.toggle-hot')
                ->middleware('check.permission:news-manage');
                Route::post('/news/bulk-action', [NewsController::class, 'bulkAction'])->name('admin.news.bulk-action')
                ->middleware('check.permission:news-manage');
                Route::post('/news/test-upload', [NewsController::class, 'testUpload'])->name('admin.news.test-upload')
                ->middleware('check.permission:news-manage');

                Route::get('/admin/contact', [ControllersContactController::class, 'index'])->name('admin.contact.index')
                ->middleware('check.permission:contact-manage');
                Route::get('/admin/contact/{id}', [ControllersContactController::class, 'show'])->name('admin.contact.show')
                ->middleware('check.permission:contact-manage');
                Route::put('/admin/contact/{id}/status', [ControllersContactController::class, 'updateStatus'])->name('admin.contact.status')
                ->middleware('check.permission:contact-manage');
                Route::delete('/admin/contact/{id}', [ControllersContactController::class, 'destroy'])->name('admin.contact.destroy')
                ->middleware('check.permission:contact-manage');
                // Role + Permission Assign
                Route::get('/assign', [RolePermissionController::class, 'assign'])
                // ->middleware('check.permission:permission-manage')
                ->name('roles.permissions.assign');
                Route::post('/assign', [RolePermissionController::class, 'storeAssign'])
                // ->middleware('check.permission:permission-manage')
                ->name('roles.permissions.storeAssign');

                Route::get('comments', [CommentSController::class, 'index'])->name('comments.index')
                ->middleware('check.permission:comment-manage');
                Route::get('/hide/{id}', [CommentSController::class, 'hide'])->name('comments.hide')
                ->middleware('check.permission:comment-manage');
                Route::get('/show/{id}', [CommentSController::class, 'showComment'])->name('comments.showComment')
                ->middleware('check.permission:comment-manage');
                Route::post('/logout', [AccountController::class, 'logout'])->name('admin.logout');
                });
