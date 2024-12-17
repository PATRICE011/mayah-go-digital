<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UpdateProfileController;
use App\Http\Controllers\SmsStatusController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/products', [ProductController::class, 'search']);

    Route::prefix('user')->group(function () {
        Route::get('/register', [AuthController::class, 'getRegister']);
        Route::get('/login', [AuthController::class, 'getLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'postLogin']);
        Route::post('/register', [AuthController::class, 'postRegister']);

        Route::get('/otp', [OtpController::class, 'showOtp']);
        Route::post('/otp', [OtpController::class, 'verifyOtp']);
        Route::post('/resend-otp', [OtpController::class, 'resendOtp']);
    });
});

// Authenticated User Routes (Role 3)
Route::middleware(['auth', RoleMiddleware::class . ':3'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('users.logout');
    Route::get('/home', [HomeController::class, 'home']);

    Route::prefix('user')->group(function () {
        Route::get('/search-products', [ProductController::class, 'search'])->name('searchProduct');

        Route::prefix('cart')->group(function () {
            Route::post('/add', [CartController::class, 'addtocart'])->name('home.inserttocart');
            Route::delete('/delete/{id}', [CartController::class, 'destroy'])->name('cartDestroy');
            Route::post('/update-cart-item/{cartItemId}', [CartController::class, 'updateCartItem']);
            Route::post('/update-quantity', [CartController::class, 'updateQuantity'])->name('cartUpdateQuantity');
        });

        Route::prefix('wishlist')->group(function () {
            Route::post('/add/{productId}', [WishlistController::class, 'addToWishlist'])->name('addtowish');
            Route::delete('/delete/{wishlistId}', [WishlistController::class, 'removeFromWishlist'])->name('wishlist.remove');
        });

        Route::prefix('payment')->group(function () {
            Route::match(['get', 'post', 'delete'], '/checkout', [CartController::class, 'processCheckout'])->name('goCheckout');
            Route::get('/create/{orderId}', [PaymentController::class, 'createPayment'])->name('cart.pay');
            Route::match(['get', 'post', 'delete'], '/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
            Route::get('/cancel', [PaymentController::class, 'paymentCancel'])->name('payment.cancel');
        });

        Route::get('/myaccount', [UserController::class, 'dashboard'])->name('myaccount.dashboard');

        Route::prefix('update-profile')->group(function () {
            Route::post('/send-code', [UpdateProfileController::class, 'sendCode'])->name('sendCode');
            Route::post('/update-profile', [UpdateProfileController::class, 'updateProfile'])->name('user.update-profile');
            Route::post('/change-password', [UpdateProfileController::class, 'changePassword'])->name('changePassword');
        });

        Route::prefix('order-status')->group(function () {
            Route::get('/invoice/{orderId}', [UserController::class, 'invoice'])->name('order.invoice');
            Route::get('/orderdetails/{orderId}', [UserController::class, 'orderDetails']);
        });
    });
});

// Admin Routes (Roles 1 & 2)
Route::middleware(['auth', RoleMiddleware::class . ':1,2'])
    ->group(function () {
       
        Route::prefix('admin')->group(function () {
            Route::get('/', [AdminController::class, 'index'])->name('admins.index');
            Route::post('/logout', [AuthController::class, 'logout'])->name('users.logout');
            Route::get('/dashboard', [AdminController::class, 'admindashboard'])->name('admins.dashboard');
        });
    });


// Public Routes
Route::get('/shop', [UserController::class, 'shop'])->name('home.shop');
Route::get('/details/{id}', [UserController::class, 'details'])->name('home.details');
Route::get('/cart', [CartController::class, 'cart'])->name('home.cart');
Route::get('/wishlist', [WishlistController::class, 'wishlist'])->name('home.wishlist');
Route::get('/about', [UserController::class, 'about'])->name('home.about');
Route::get('/privacypolicy', [UserController::class, 'privacypolicy'])->name('home.privacypolicy');
// Route::get('/checkout', [UserController::class, 'otp'])->name('home.checkout');
Route::post('/filter-products', [UserController::class, 'filterProducts']);
