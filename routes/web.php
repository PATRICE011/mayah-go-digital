<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OnlineOrdersController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductReportController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\UpdateProfileController;

use App\Http\Controllers\SmsStatusController;
use App\Http\Controllers\StockController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;
use App\Models\Product;

// Guest Routes
Route::middleware(['guest'])->group(function () {

    Route::prefix('user')->group(function () {
        Route::get('/register', [AuthController::class, 'getRegister']);
        Route::get('/login', [AuthController::class, 'getLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'postLogin']);
        Route::post('/register', [AuthController::class, 'postRegister']);
        Route::post('/validate-register', [AuthController::class, 'validateRegister'])->name('validate.register');

        Route::get('/otp', [OtpController::class, 'showOtp']);
        Route::post('/otp', [OtpController::class, 'verifyOtp']);
        Route::post('/resend-otp', [OtpController::class, 'resendOtp']);
    });
});

// Authenticated User Routes (Role 3 - Resident)
Route::middleware(['auth', RoleMiddleware::class . ':3'])->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('/', [HomeController::class, 'home']);
        Route::post('/logout', [AuthController::class, 'logout'])->name('users.logout');

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
            Route::post('/validate-old-password', [UpdateProfileController::class, 'validateOldPassword']);
        });

        Route::prefix('order-status')->group(function () {
            Route::get('/invoice/{orderId}', [UserController::class, 'invoice'])->name('order.invoice');
            Route::get('/orderdetails/{orderId}', [UserController::class, 'orderDetails']);
        });
    });
});

// Admin & Staff Routes (Roles 1 & 2 - Admin, Staff)
Route::middleware(['auth', RoleMiddleware::class . ':1,2'])
    ->prefix('admin')->group(function () {
        // Route::get('/', [AdminController::class, 'index'])->name('admins.index');
        Route::post('/logout', [AdminController::class, 'logout'])->name('admins.logout');
        Route::get('/', [AdminController::class, 'admindashboard'])->name('admins.dashboard');


        Route::get('/products', [productController::class, 'adminproducts'])->name('admins.adminproducts');
        Route::post('/store-products', [productController::class, 'store']);
        Route::post('/update-product/{id}', [productController::class, 'updateProduct'])->name('admin.update-product');
        Route::delete('/delete-product/{id}', [productController::class, 'deleteProduct'])->name('admin.delete-product');
        Route::get('/all-categories', [productController::class, 'getAllCategories']);
        Route::get('/products/export-products', [productController::class, 'export']);

        Route::get('/categories', [categoryController::class, 'admincategories'])->name('admins.admincategories');
        Route::post('/store-categories', [categoryController::class, 'storeCategory'])->name('categories.store');
        Route::delete('/delete-category/{id}', [categoryController::class, 'destroy']);
        Route::post('update-category/{id}', [categoryController::class, 'update']);

        Route::get('/employees', [EmployeeController::class, 'adminemployee'])->name("admins.adminemployee");
        Route::post('/employees/store', [EmployeeController::class, 'store']);
        Route::delete('/employees/delete/{id}', [EmployeeController::class, 'delete']);
        Route::put('/employees/update/{id}', [EmployeeController::class, 'update']);
        Route::get('/employees/export', [EmployeeController::class, 'exportEmployees']);

        Route::get('/customers', [CustomerController::class, 'admincustomers'])->name("admins.admincustomers");
        Route::delete('/customers/delete/{id}', [CustomerController::class, 'delete']);
        Route::put('/customers/update/{id}', [CustomerController::class, 'update']);
        Route::get('/customers/export', [CustomerController::class, 'exportCustomers']);

        Route::get('/products-report', [ProductReportController::class, 'adminproductsreport'])->name('admins.adminproductsreport');
        Route::get('/print-product-report', [ProductReportController::class, 'exportProductsReport'])->name('admins.printProductReport');

        Route::get('/sales-report', [SalesReportController::class, 'adminsalesreport'])->name('admins.adminsalesreport');
        Route::get('/export-sales-report', [SalesReportController::class, 'exportSalesReport'])->name('admins.exportSalesReport');


        Route::get('/online-orders', [OnlineOrdersController::class, 'adminonlineorders'])->name('admins.adminonlineorders');
        Route::get('/order-details/{id}', [OnlineOrdersController::class, 'getOrderDetails'])
            ->name('admins.getOrderDetails');
        Route::post('/update-order-status/{id}', [OnlineOrdersController::class, 'updateOrderStatus'])
            ->name('admins.updateOrderStatus');



        Route::prefix('pos')->group(function () {
            Route::get('/', [PosController::class, 'adminpos'])->name('admins.adminpos');
            Route::get('/categories', [PosController::class, 'getCategories'])->name('categories.get');
            Route::get('/products', [PosController::class, 'getProducts'])->name('products.get');
            Route::post('/cart/add', [PosController::class, 'addToCart'])->name('cart.add');
            Route::get('/cart', [PosController::class, 'getCart'])->name('cart.get');
            Route::post('/cart/update', [PosController::class, 'updateCart'])->name('cart.update'); // Update cart
            Route::post('/checkout', [PosController::class, 'checkout'])->name('checkout');
            Route::get('/report', [PosController::class, 'adminposreport'])->name('admins.adminposreport');
            Route::get('/pos/export', [PosController::class, 'exportPosReport'])->name('admins.export.report');
            Route::match(['post', 'delete'], '/delete/{productId}', [PosController::class, 'destroyPOS'])
                ->name('cartDestroyPOS');
            Route::get('/products/search', [PosController::class, 'search'])->name('products.search');
            Route::post('/cart/clear', [PosController::class, 'clear'])->name('cart.clear');

            Route::get('/products/checkStock', [PosController::class, 'checkStock'])->name('products.checkStock');
        });

        Route::prefix('stocks')->group(function(){
            Route::get('/stock-in', [StockController::class, 'stock_in_report']);
            Route::get('/stock-out', [StockController::class, 'stock_out_report']);
            Route::get('/stock-inventory', [StockController::class, 'inventory_report']);
        });
       


        Route::get('/pos-orders', [AdminController::class, 'adminposorders'])->name('admins.adminposorders');

        Route::get('/return-and-refunds', [AdminController::class, 'adminrefund'])->name('admins.adminrefund');
        Route::get('/users/administrators', [AdminController::class, 'adminadministrators'])->name('admins.adminadministrators');

        Route::get('/audit-trail', [AuditController::class, 'adminaudit'])->name('admins.adminaudit');


        Route::get('/products/export', function () {
            $products = Product::with('category')->get(); // Fetch all products with categories
            return view('admins.export-products', compact('products'));
        })->name('products.export');

        Route::get('/print-categories', [CategoryController::class, 'printCategories'])->name('categories.print');

        Route::get('/stocks', [AdminController::class, 'adminstocks'])->name('admins.adminstocks');
        Route::get('/stocks/report', [StockController::class, 'index']);
        Route::get('stocks/report/export', [StockController::class, 'export']);
    });

// Public Routes (accessible by all)
Route::get('/', [HomeController::class, 'index']);
Route::get('/products', [ProductController::class, 'search']); // Product search

Route::get('/shop', [UserController::class, 'shop'])->name('home.shop'); // Shop page
Route::get('/details/{id}', [UserController::class, 'details'])->name('home.details'); // Product details page
Route::get('/cart', [CartController::class, 'cart'])->name('home.cart'); // View cart page
Route::get('/wishlist', [WishlistController::class, 'wishlist'])->name('home.wishlist'); // View wishlist page
Route::get('/about', [UserController::class, 'about'])->name('home.about'); // About page
Route::get('/privacypolicy', [UserController::class, 'privacypolicy'])->name('home.privacypolicy'); // Privacy Policy page

Route::post('/filter-products', [UserController::class, 'filterProducts']); // Filter products via POST

// Route::get('/search-products', [ProductController::class, 'search'])->name('searchProduct');
