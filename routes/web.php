<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;



// public routes
Route::get('/', [HomeController::class, 'index'])->name('home.index');
// search
Route::get('/products', [ProductController::class, 'search'])->name('searchProduct');

// Guest Routes
Route::middleware(['guest'])->group(function () {
    
    
    Route::get('/register', [UserController::class, 'getRegister'])->name('users.register');
    Route::get('/login', [UserController::class, 'getLogin'])->name('users.login');
    
    Route::post('/login', [UserController::class, 'postLogin'])->name('login');
    Route::post('/register', [UserController::class, 'postRegister'])->name('users.makereg');
    
    // OTP routes
    Route::get('/otp', [OtpController::class, 'showOtp'])->name('users.otp');
    Route::post('/otp', [OtpController::class, 'verifyOtp'])->name('users.verifyOtp');
    Route::post('/resend-otp', [OtpController::class, 'resendOtp'])->name('users.resendOtp');
    
});

// Authenticated User Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [UserController::class, 'viewDashboard'])->name('users.usersdashboard');
    Route::post('/user/logout', [UserController::class, 'logout'])->name('users.logout');
    
    // // Cart routes
    Route::get('/cart', [CartController::class, 'showCart'])->name('home.cartinside');
    Route::post('/cart/add', [CartController::class, 'addtocart'])->name('home.inserttocart');

    Route::get('/checkout', [CartController::class, 'checkout'])->name('home.checkout');
    
});

// Admin Routes
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admins.index');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admins.logout');

    // dashboard
    Route::get('admin/dashboard', [AdminController::class, 'showDashboard'])->name('admins.dashboard');
    
    // inventory
    Route::get('/inventory', [AdminController::class, 'showInventory'])->name('admins.inventory');
    Route::post('/inventory', [ProductController::class, 'getProduct'])->name('admins.insertProduct');
    Route::put('/admin/inventory/{id}', [ProductController::class, 'update'])->name('admins.inventory.update');
    Route::delete('/admin/inventory/{id}', [ProductController::class, 'destroy'])->name('admins.inventory.destroy');
    
    // categories
    Route::get('/categories', [AdminController::class, 'showCategories'])->name('admins.category');
    Route::post('/categories', [categoryController::class, 'getCategory'])->name('admins.insertCategory');
    Route::put('/admin/categories/{id}', [categoryController::class, 'update'])->name('admins.category.update');
    Route::delete('/admin/categories/{id}', [categoryController::class, 'destroy'])->name('admins.category.destroy');
});
