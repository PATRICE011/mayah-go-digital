<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SmsStatusController;

// public routes
Route::get('/', [HomeController::class, 'index'])->name('home.index');
// search
Route::get('/products', [ProductController::class, 'search'])->name('searchProduct');

// Guest Routes
Route::middleware(['guest'])->group(function () {
    // public routes
    Route::get('/', [HomeController::class, 'index'])->name('home.index');
    Route::prefix('user')->group(function () { 
        Route::get('/register', [UserController::class, 'getRegister'])->name('users.register');
        Route::get('/login', [UserController::class, 'getLogin'])->name('users.login');
        
        Route::post('/login', [UserController::class, 'postLogin'])->name('login');
        Route::post('/register', [UserController::class, 'postRegister'])->name('users.makereg');
        
        // OTP routes
        Route::get('/otp', [OtpController::class, 'showOtp'])->name('users.otp');
        Route::post('/otp', [OtpController::class, 'verifyOtp'])->name('users.verifyOtp');
        Route::post('/resend-otp', [OtpController::class, 'resendOtp'])->name('users.resendOtp');
    }); 
});


// Authenticated User Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [UserController::class, 'viewDashboard'])->name('users.usersdashboard');
    Route::post('/logout', [UserController::class, 'logout'])->name('users.logout');
    
    // // Cart routes
    Route::prefix('cart')->group(function(){
        Route::get('/show', [CartController::class, 'showCart'])->name('home.cartinside');
        Route::post('/add', [CartController::class, 'addtocart'])->name('home.inserttocart');
        Route::delete('/delete/{id}', [CartController::class, 'destroy'])->name('cartDestroy');
        
        Route::post('/update/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');

    });

    // ===== MY ORDERS ====
    Route::get('/myorders',[CartController::class, 'viewOrders'])->name('home.myorders');

   Route::prefix('payment')->group(function(){
         // paymongo
        Route::match(['get', 'post'], '/checkout', [CartController::class, 'processCheckout'])->name('goCheckout');
        Route::get('/create/{orderId}', [PaymentController::class, 'createPaymentTest'])->name('cart.pay');
        Route::match(['get', 'post'],'/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');

   });
});

// Admin Routes
Route::middleware('auth:admin')->group(function () {
   Route::prefix('admin')->group(function(){
    Route::get('/index', [AdminController::class, 'index'])->name('admins.index');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admins.logout');

    // dashboard
    Route::get('/dashboard', [AdminController::class, 'showDashboard'])->name('admins.dashboard');
    
    // orders
    // Route::get('/orders', [AdminController::class, 'showOrders'])->name('admins.orders');
    Route::get('/orders', [AdminController::class, 'onlineOrders'])->name('admins.orders');
    Route::get('/orders/view/{id}', [AdminController::class, 'showView'])->name('admins.view');

    // status
    Route::post('/orders/{order}/confirm', [SmsStatusController::class, 'confirmOrder'])->name('orders.confirm');
    Route::post('/orders/{order}/reject', [SmsStatusController::class, 'rejectOrder'])->name('orders.reject');
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


