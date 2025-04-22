<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\Audit;
use App\Models\User;


class AdminController extends Controller
{

    public function index()
    {
        // Metrics for the cards
        $totalCustomers = DB::table('users_area')->count();
        $totalOrders = DB::table('orders')->count();
        $totalProducts = DB::table('products')->count();

        // Growth rate calculation
        $growthRate = $this->calculateGrowthRate($totalOrders);

        // Top Selling Products
        $topSellingProducts = $this->getTopSellingProducts(10);

        // Revenue calculations
        $todaysEarnings = $this->calculateRevenueForDate(now());
        $currentWeekEarnings = $this->calculateRevenueForDateRange(now()->startOfWeek(), now()->endOfWeek());
        $previousWeekEarnings = $this->calculateRevenueForDateRange(now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek());

        // Total sales by category
        $salesByCategory = $this->getSalesByCategory();

        $colors = [
            'Biscuits' => '#007bff',
            'Dairy' => '#dc3545',
            'Drinks' => '#ffc107',
            'School Supplies' => '#17a2b8',
            // Add more categories and colors if needed
        ];

        return view("admins.index", compact(
            'totalCustomers',
            'totalOrders',
            'totalProducts',
            'growthRate',
            'topSellingProducts',
            'todaysEarnings',
            'currentWeekEarnings',
            'previousWeekEarnings',
            'salesByCategory',
            'colors'
        ));
    }

    /**
     * Calculate month-over-month growth rate.
     */
    private function calculateGrowthRate($currentOrders)
    {
        // Get the count of orders from the previous month
        $previousOrders = DB::table('orders')
            ->whereBetween('created_at', [
                now()->subMonth()->startOfMonth(),
                now()->subMonth()->endOfMonth()
            ])
            ->count();

        // Debugging log
        Log::info("Current Orders: $currentOrders, Previous Orders: $previousOrders");

        if ($previousOrders == 0) {
            return 0; // Avoid division by zero
        }

        // Calculate growth rate
        $growthRate = (($currentOrders - $previousOrders) / $previousOrders) * 100;

        return round($growthRate, 2);
    }


    /**
     * Get top-selling products.
     */
    private function getTopSellingProducts($limit)
    {
        return DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('users_area', 'orders.user_id', '=', 'users_area.id')
            ->select(
                'products.id as product_id',
                'products.product_name',
                'products.product_image',
                'order_items.quantity',
                'order_items.price',
                'orders.created_at as order_time',
                'users_area.name as customer_name'
            )
            ->orderByDesc('order_items.quantity')
            ->take($limit)
            ->get();
    }

    /**
     * Calculate revenue for a specific date.
     */
    private function calculateRevenueForDate($date)
    {
        return DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->whereDate('orders.created_at', $date)
            ->sum(DB::raw('order_items.quantity * order_items.price'));
    }

    /**
     * Calculate revenue for a specific date range.
     */
    private function calculateRevenueForDateRange($startDate, $endDate)
    {
        return DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->sum(DB::raw('order_items.quantity * order_items.price'));
    }

    /**
     * Get total sales by category.
     */
    private function getSalesByCategory()
    {
        return DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.category_name as category_name', DB::raw('SUM(order_items.quantity * order_items.price) as total_sales'))
            ->groupBy('categories.category_name') // Use the correct column name here
            ->get();
    }

    private function getDailyRevenueForWeek($startDate, $endDate)
    {
        // Fetch daily revenue for the week
        $revenues = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DAYOFWEEK(orders.created_at) as day'), // Day of the week (1 = Sunday, 7 = Saturday)
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue') // Sum of revenue
            )
            ->groupBy('day')
            ->pluck('total_revenue', 'day') // Returns an associative array: [day => total_revenue]
            ->toArray();

        // Ensure all 7 days are represented with 0 revenue if missing
        $weeklyRevenue = array_fill(1, 7, 0); // Sunday (1) to Saturday (7)

        foreach ($revenues as $day => $revenue) {
            $weeklyRevenue[$day] = $revenue;
        }

        return array_values($weeklyRevenue); // Return as an indexed array
    }

    public function admindashboard()
    {
        // Fetching current totals
        $totalCustomers = DB::table('users_area')->count();
        $totalCustomersLastWeek = DB::table('users_area')
            ->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
            ->count();
    
        $totalOrders = DB::table('orders')->count();
        $totalOrdersLastWeek = DB::table('orders')
            ->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
            ->count();
    
        $totalProducts = DB::table('products')->count();
        $totalProductsLastWeek = DB::table('products')
            ->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
            ->count();
    
        $totalCategories = DB::table('categories')->count();
        $totalCategoriesLastWeek = DB::table('categories')
            ->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
            ->count();
    
        // Calculating growth percentage for customers
        $customerGrowth = $totalCustomersLastWeek > 0 ? 
            (($totalCustomers - $totalCustomersLastWeek) / $totalCustomersLastWeek) * 100 : 0;
    
        // Calculating growth percentage for products
        $productGrowth = $totalProductsLastWeek > 0 ?
            (($totalProducts - $totalProductsLastWeek) / $totalProductsLastWeek) * 100 : 0;
    
        // Calculating growth rate for orders
        $orderGrowth = $totalOrdersLastWeek > 0 ?
            (($totalOrders - $totalOrdersLastWeek) / $totalOrdersLastWeek) * 100 : 0;
    
        // Calculating growth rate for categories
        $categoryGrowth = $totalCategoriesLastWeek > 0 ?
            (($totalCategories - $totalCategoriesLastWeek) / $totalCategoriesLastWeek) * 100 : 0;
    
        // Assuming growthRate is an overarching metric, average of individual growths
        $growthRate = ($customerGrowth + $productGrowth + $orderGrowth + $categoryGrowth) / 4;
    
        // Other metrics
        $topSellingProducts = $this->getTopSellingProducts(10);
        $todaysEarnings = $this->calculateRevenueForDate(now());
        $currentWeekEarnings = $this->calculateRevenueForDateRange(now()->startOfWeek(), now()->endOfWeek());
        $previousWeekEarnings = $this->calculateRevenueForDateRange(now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek());
        $salesByCategory = $this->getSalesByCategory();
        $currentWeekRevenue = $this->getDailyRevenueForWeek(now()->startOfWeek(), now()->endOfWeek());
        $previousWeekRevenue = $this->getDailyRevenueForWeek(now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek());
    
        // Colors for categories
        $colors = [
            'Biscuits' => '#007bff',
            'Dairy' => '#dc3545',
            'Drinks' => '#ffc107',
            'School Supplies' => '#17a2b8',
        ];
    
        // Passing all data to the view, including dynamic growth rates
        return view('admins.dashboard', compact(
            'totalCustomers',
            'totalOrders',
            'totalProducts',
            'totalCategories',
            'customerGrowth',
            'productGrowth',
            'orderGrowth',
            'categoryGrowth',
            'growthRate',
            'topSellingProducts',
            'todaysEarnings',
            'currentWeekEarnings',
            'previousWeekEarnings',
            'salesByCategory',
            'colors',
            'currentWeekRevenue',
            'previousWeekRevenue'
        ));
    }
    

    public function adminadministrators()
    {
        return view("admins.adminadministrators");
    }

    // public function adminstocks()
    // {
    //     return view("admins.adminstocks");
    // }

    public function adminposorders()
    {
        return view("admins.adminposorders");
    }

  

    public function adminrefund()
    {
        return view("admins.adminrefund");
    }

 

    public function logout(Request $request)
    {
        Audit::create([
            'user_id' => Auth::id(),
            'action' => 'logout',
            'model_type' => User::class,
            
        ]);
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();


        return redirect('/')->with('message', 'Logout Successful');
    }
}
