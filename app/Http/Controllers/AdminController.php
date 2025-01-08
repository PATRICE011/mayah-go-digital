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
        $previousOrders = DB::table('orders')
            ->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->count();

        return $previousOrders > 0
            ? (($currentOrders - $previousOrders) / $previousOrders) * 100
            : 0;
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

    public function admindashboard()
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


        return view('admins.dashboard', compact(
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


    public function adminadministrators()
    {
        return view("admins.adminadministrators");
    }


    public function adminaudit(Request $request)
    {
        $query = Audit::query();

        // Apply filters
        if ($request->filled('name')) {
            // Filter by user name
            $query->whereHas('user', function ($userQuery) use ($request) {
                $userQuery->where('name', 'like', '%' . $request->name . '%');
            });
        }

        if ($request->filled('role')) {
            // Filter by role ID (restricted to admin and staff only)
            $query->whereHas('user', function ($userQuery) use ($request) {
                $userQuery->whereIn('role_id', [1, 2]) // Restrict to admin and staff
                    ->where('role_id', $request->role);
            });
        } else {
            // Default restriction to admin and staff roles
            $query->whereHas('user', function ($userQuery) {
                $userQuery->whereIn('role_id', [1, 2]);
            });
        }

        if ($request->filled('date')) {
            // Filter by specific date
            $query->whereDate('created_at', $request->date);
        }

        // Sort by latest (descending order)
        $query->orderBy('created_at', 'desc');

        // Retrieve audits with associated user and role data, paginated by 6
        $audits = $query->with(['user.role'])->paginate(6);

        // Preserve filters in pagination links
        $audits->appends($request->all());

        return view('admins.adminaudit', compact('audits'));
    }


    public function admincustomers()
    {
        return view("admins.admincustomers");
    }

  

    public function adminstocks()
    {
        return view("admins.adminstocks");
    }

    public function adminposorders()
    {
        return view("admins.adminposorders");
    }

    public function adminonlineorders()
    {
        return view("admins.adminonlineorders");
    }

    public function adminrefund()
    {
        return view("admins.adminrefund");
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();


        return redirect('/')->with('message', 'Logout Successful');
    }
}
