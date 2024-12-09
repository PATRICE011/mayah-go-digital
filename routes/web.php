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
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SmsStatusController;
use App\Http\Middleware\RoleMiddleware;

Route::middleware(['guest'])->group(function () {
    // Guest Routes
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/products', [ProductController::class, 'search']);

    Route::prefix('user')->group(function () {
        // Authentication Routes
        Route::get('/register', [AuthController::class, 'getRegister']);
        Route::get('/login', [AuthController::class, 'getLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'postLogin']);
        Route::post('/register', [AuthController::class, 'postRegister']);

        // OTP Routes
        Route::get('/otp', [OtpController::class, 'showOtp']);
        Route::post('/otp', [OtpController::class, 'verifyOtp']);
        Route::post('/resend-otp', [OtpController::class, 'resendOtp']);
    });
});


// Authenticated User Routes (Role 3)
Route::middleware(['auth', RoleMiddleware::class . ':3'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('users.logout');
    Route::get('/home', [HomeController::class, 'home']);
    // User Profile & Settings
    Route::prefix('user')->group(function () {
        
        Route::post('/logout', [AuthController::class, 'logout'])->name('users.logout');

        // Product Search
        Route::get('/search-products', [ProductController::class, 'search'])->name('searchProduct');

        // Cart Routes
        Route::prefix('cart')->group(function () {
            // Route::get('/show', [CartController::class, 'showCart'])->name('home.cartinside');
            Route::post('/add', [CartController::class, 'addtocart'])->name('home.inserttocart');
            Route::delete('/delete/{id}', [CartController::class, 'destroy'])->name('cartDestroy');
            Route::post('/update/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');
        });
        Route::prefix('wishlist')->group(function () {
            Route::post('/add/{productId}', [WishlistController::class, 'addToWishlist'])->name('addtowish');
            Route::delete('/delete/{wishlistId}', [WishlistController::class, 'removeFromWishlist'])->name('wishlist.remove');
        });
        // Payment Routes
        Route::prefix('payment')->group(function () {
            Route::match(['get', 'post'], '/checkout', [CartController::class, 'processCheckout'])->name('goCheckout');
            Route::get('/create/{orderId}', [PaymentController::class, 'createPaymentTest'])->name('cart.pay');
            Route::match(['get', 'post'], '/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
        });

        // My Orders
        Route::get('/myorders/view{section?}', [SettingsController::class, 'viewMyorders'])->name('home.viewmyorders');


        // User Dashboard
        Route::get('/myaccount', [UserController::class, 'dashboard'])->name('myaccount.dashboard');
        

       // My Orders
       Route::get('/myorders/view{section?}', [SettingsController::class, 'viewMyorders'])->name('home.viewmyorders');

       // Show the update profile form
       Route::get('/update-profile', [userController::class, 'updateProfileForm'])->name('user.update-profile.form');

       // Handle the profile update form submission (with OTP generation)
       Route::post('/update-profile', [userController::class, 'updateProfile'])->name('user.update-profile');

       // Verify OTP and allow profile update
       Route::post('/verify-otp', [userController::class, 'verifyOtp'])->name('user.verify-otp');
    });
});

// Admin Routes (Roles 1 & 2)
Route::middleware(['auth', RoleMiddleware::class . ':1,2'])->group(function () {
    Route::prefix('admin')->group(function () {
        // Admin Dashboard
        Route::get('/index', [AdminController::class, 'index'])->name('admins.index');
        Route::post('/logout', [AdminController::class, 'logout'])->name('admins.logout');
        Route::get('/dashboard', [AdminController::class, 'showDashboard'])->name('admins.dashboard');

        // Orders
        Route::get('/orders', [AdminController::class, 'onlineOrders'])->name('admins.orders');
        Route::get('/orders/view/{id}', [AdminController::class, 'showView'])->name('admins.view');
        Route::post('/orders/{order}/confirm', [SmsStatusController::class, 'confirmOrder'])->name('orders.confirm');
        Route::post('/orders/{order}/reject', [SmsStatusController::class, 'rejectOrder'])->name('orders.reject');
        Route::post('/orders/{order}/ready-for-pickup', [SmsStatusController::class, 'readyOrder'])->name('orders.ready');
        Route::post('/orders/{order}/completed', [SmsStatusController::class, 'completeOrder'])->name('orders.complete');
        Route::post('/orders/{order}/refund', [SmsStatusController::class, 'refundOrder'])->name('orders.refund');

        // POS (Point of Sale)
        Route::get('/pos', [AdminController::class, 'viewPOS'])->name('admins.pos');
        Route::get('/posorders', [AdminController::class, 'viewPOSorders'])->name('admins.posOrders');
        Route::get('/viewposorders', [AdminController::class, 'showPOSorders'])->name('admins.viewposOrders');

        // Inventory Management
        Route::get('/inventory', [AdminController::class, 'showInventory'])->name('admins.inventory');
        Route::post('/inventory', [ProductController::class, 'getProduct'])->name('admins.insertProduct');
        Route::put('/inventory/{id}', [ProductController::class, 'update'])->name('admins.inventory.update');
        Route::delete('/inventory/{id}', [ProductController::class, 'destroy'])->name('admins.inventory.destroy');

        // Categories Management
        Route::get('/categories', [AdminController::class, 'showCategories'])->name('admins.category');
        Route::post('/categories', [CategoryController::class, 'getCategory'])->name('admins.insertCategory');
        Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('admins.category.update');
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('admins.category.destroy');
    });
});

// Public User Routes (No authentication required)
Route::get('/shop', [UserController::class, 'shop'])->name('home.shop');
Route::get('/details', [UserController::class, 'details'])->name('home.details');
Route::get('/cart', [cartController::class, 'cart'])->name('home.cart');
Route::get('/wishlist', [WishlistController::class, 'wishlist'])->name('home.wishlist');

// Routes for checkout and my account
Route::get('/checkout', [UserController::class, 'otp'])->name('home.checkout');
Route::get('/orderdetails', [UserController::class, 'orderDetails']);

Route::post('/filter-products', [UserController::class, 'filterProducts']);