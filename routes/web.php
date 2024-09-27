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


// public routes
Route::get('/', [HomeController::class, 'index'])->name('home.index');
// search
Route::get('/products', [ProductController::class, 'search'])->name('searchProduct');

// Guest Routes
Route::middleware(['guest'])->group(function () {
    // public routes
    Route::get('/', [HomeController::class, 'index'])->name('home.index');
<<<<<<< HEAD
    
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
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cartDestroy');
    Route::post('/cart/update/{id}', 'CartController@update')->name('cartUpdate');

    Route::get('/checkout', [CartController::class, 'checkout'])->name('home.checkout');
    // Correct route capitalization
    Route::post('/update-quantity', [CartController::class, 'updateQuantity'])->name('cartUpdate');
    Route::post('/checkout', [CartController::class, 'processCheckout'])->name('goCheckout');

    // my orders
    Route::get('/myorders',[CartController::class, 'viewOrders'])->name('home.myorders');
    
    // paymongo
    Route::get('/pay/{cartId}', [PaymentController::class, 'createPaymentMain'])->name('cart.pay');
    Route::get('/pay/{orderId}', [PaymentController::class, 'createPayment'])->name('order.pay');
    Route::get('/success', [PaymentController::class, 'success'])->name('order.success');
=======
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
        Route::post('/update/{id}', 'CartController@update')->name('cartUpdate');
        Route::post('/update-quantity', [CartController::class, 'updateQuantity'])->name('cartUpdate');
    });

    // ===== MY ORDERS ====
    Route::get('/myorders',[CartController::class, 'viewOrders'])->name('home.myorders');

   Route::prefix('payment')->group(function(){
         // paymongo
        Route::match(['get', 'post'], '/checkout', [CartController::class, 'processCheckout'])->name('goCheckout');
        Route::get('/create/{orderId}', [PaymentController::class, 'createPaymentTest'])->name('cart.pay');
        Route::get('/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');

   });
>>>>>>> 49d8031bb7a2b73a36d608fa139f3b6cffe92565
});

// Admin Routes
Route::middleware('auth:admin')->group(function () {
<<<<<<< HEAD
    Route::get('/admin', [AdminController::class, 'index'])->name('admins.index');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admins.logout');

    // dashboard
    Route::get('admin/dashboard', [AdminController::class, 'showDashboard'])->name('admins.dashboard');
=======
   Route::prefix('admin')->group(function(){
    Route::get('/index', [AdminController::class, 'index'])->name('admins.index');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admins.logout');

    // dashboard
    Route::get('/dashboard', [AdminController::class, 'showDashboard'])->name('admins.dashboard');
>>>>>>> 49d8031bb7a2b73a36d608fa139f3b6cffe92565
    
    // inventory
    Route::get('/inventory', [AdminController::class, 'showInventory'])->name('admins.inventory');
    Route::post('/inventory', [ProductController::class, 'getProduct'])->name('admins.insertProduct');
<<<<<<< HEAD
    Route::put('/admin/inventory/{id}', [ProductController::class, 'update'])->name('admins.inventory.update');
    Route::delete('/admin/inventory/{id}', [ProductController::class, 'destroy'])->name('admins.inventory.destroy');
=======
    Route::put('/inventory/{id}', [ProductController::class, 'update'])->name('admins.inventory.update');
    Route::delete('/inventory/{id}', [ProductController::class, 'destroy'])->name('admins.inventory.destroy');
>>>>>>> 49d8031bb7a2b73a36d608fa139f3b6cffe92565
    
    // categories
    Route::get('/categories', [AdminController::class, 'showCategories'])->name('admins.category');
    Route::post('/categories', [categoryController::class, 'getCategory'])->name('admins.insertCategory');
<<<<<<< HEAD
    Route::put('/admin/categories/{id}', [categoryController::class, 'update'])->name('admins.category.update');
    Route::delete('/admin/categories/{id}', [categoryController::class, 'destroy'])->name('admins.category.destroy');
});

// Add this to your web.php for quick testing or create a new command
Route::get('/test-sms', function () {
    $admin = App\Models\Admin::first(); // Assuming Admin is correctly set up
    if ($admin) {
        app('App\Console\Commands\CheckLowStock')->sendSms($admin->mobile, 'Test SMS from Laravel');
        return 'SMS sent!';
    }
    return 'Admin not found.';
});
=======
    Route::put('/categories/{id}', [categoryController::class, 'update'])->name('admins.category.update');
    Route::delete('/categories/{id}', [categoryController::class, 'destroy'])->name('admins.category.destroy');
   });
});

>>>>>>> 49d8031bb7a2b73a36d608fa139f3b6cffe92565

