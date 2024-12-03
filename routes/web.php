<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SmsStatusController;
use App\Http\Middleware\RoleMiddleware;


Route::middleware(['guest'])->group(function () {
    
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/products', [ProductController::class, 'search']);

    
    Route::prefix('user')->group(function () { 
        Route::get('/register', [AuthController::class, 'getRegister']);
        Route::get('/login', [AuthController::class, 'getLogin'])->name('login');
        
        Route::post('/login', [AuthController::class, 'postLogin'] );
        Route::post('/register', [AuthController::class, 'postRegister']);
        
        Route::get('/otp', [OtpController::class, 'showOtp']);
        Route::post('/otp', [OtpController::class, 'verifyOtp']);
        Route::post('/resend-otp', [OtpController::class, 'resendOtp']);
    }); 
});

// Authenticated User Routes
Route::middleware(['auth', RoleMiddleware::class . ':3'])->group(function () {
    Route::get('/home', [HomeController::class, 'home']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('users.logout');
    Route::get('/search-products', [ProductController::class, 'search'])->name('searchProduct');
    // // Cart routes
    Route::prefix('cart')->group(function(){
        Route::get('/show', [CartController::class, 'showCart'])->name('home.cartinside');
        Route::post('/add', [CartController::class, 'addtocart'])->name('home.inserttocart');
        Route::delete('/delete/{id}', [CartController::class, 'destroy'])->name('cartDestroy');
        
        Route::post('/update/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');
    });

    // ===== MY ORDERS ====
    // Route::get('/myorders',[CartController::class, 'viewOrders'])->name('home.myorders');
   
    Route::get('/post-success', [PaymentController::class, 'postSuccess']);
    Route::get('/post-error', [PaymentController::class, 'postError']);

    Route::prefix('payment')->group(function(){
        // paymongo
        Route::match(['get', 'post'], '/checkout', [CartController::class, 'processCheckout'])->name('goCheckout');
        Route::get('/create/{orderId}', [PaymentController::class, 'createPaymentTest'])->name('cart.pay');
        Route::match(['get', 'post'],'/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
    });

    // ==== SETTINGS =====
    Route::get('/settings',[SettingsController::class, 'viewSettings'])->name('settings');
    Route::get('/myorders/view{section?}', [SettingsController::class, 'viewMyorders'])->name('home.viewmyorders');
});

// Admin Routes
Route::middleware(['auth', RoleMiddleware::class . ':1,2'])->group(function () {
   Route::prefix('admin')->group(function(){
    Route::get('/index', [AdminController::class, 'index'])->name('admins.index');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admins.logout');

    // dashboard
    Route::get('/dashboard', [AdminController::class, 'showDashboard'])->name('admins.dashboard');
    
    // orders
    // Route::get('/orders', [AdminController::class, 'showOrders'])->name('admins.orders');
    Route::get('/orders', [AdminController::class, 'onlineOrders'])->name('admins.orders');
    Route::get('/orders/view/{id}', [AdminController::class, 'showView'])->name('admins.view');

    // POS
    Route::get('/pos', [AdminController::class, 'viewPOS'])->name('admins.pos');
    Route::get('/posorders', [AdminController::class, 'viewPOSorders'])->name('admins.posOrders');
    Route::get('/viewposorders', [AdminController::class, 'showPOSorders'])->name('admins.viewposOrders');

    // status
    Route::post('/orders/{order}/confirm', [SmsStatusController::class, 'confirmOrder'])->name('orders.confirm');
    Route::post('/orders/{order}/reject', [SmsStatusController::class, 'rejectOrder'])->name('orders.reject');
    Route::post('/orders/{order}/ready-for-pickup', [SmsStatusController::class, 'readyOrder'])->name('orders.ready');
    Route::post('/orders/{order}/completed', [SmsStatusController::class, 'completeOrder'])->name('orders.complete');
    Route::post('/orders/{order}/refund', [SmsStatusController::class, 'refundOrder'])->name('orders.refund');
    // inventory
    Route::get('/inventory', [AdminController::class, 'showInventory'])->name('admins.inventory');
    Route::post('/inventory', [ProductController::class, 'getProduct'])->name('admins.insertProduct');
    Route::put('/inventory/{id}', [ProductController::class, 'update'])->name('admins.inventory.update');
    Route::delete('/inventory/{id}', [ProductController::class, 'destroy'])->name('admins.inventory.destroy');
    
    // categories
    Route::get('/categories', [AdminController::class, 'showCategories'])->name('admins.category');
    Route::post('/categories', [categoryController::class, 'getCategory'])->name('admins.insertCategory');
    Route::put('/categories/{id}', [categoryController::class, 'update'])->name('admins.category.update');
    Route::delete('/categories/{id}', [categoryController::class, 'destroy'])->name('admins.category.destroy');
   });
});

Route::get('/shop', [UserController::class, 'shop'])->name('home.shop');
Route::get('/details', [UserController::class, 'details'])->name('home.details');
Route::get('/cart', [UserController::class, 'cart'])->name('home.cart');
Route::get('/wishlist', [UserController::class, 'wishlist'])->name('home.wishlist');
// Route::get('/otp', [UserController::class, 'otp'])->name('users.otp');
Route::get('/checkout', [UserController::class, 'otp'])->name('home.checkout');
Route::get('/myaccount', [UserController::class,'MyAccount']);