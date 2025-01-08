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

    public function adminproducts()
    {
        $categories = Category::all();

        if (request()->ajax()) {
            $query = request('query', ''); // Search term
            $categorySlug = request('category', ''); // Selected category slug
            $minPrice = request('minPrice', 0); // Minimum price filter
            $maxPrice = request('maxPrice', null); // Maximum price filter
            $status = request('status', ''); // Active/Inactive filter

            $products = Product::with('category')
                ->when($query, function ($queryBuilder) use ($query) {
                    $queryBuilder->where('product_name', 'like', "%$query%");
                })
                ->when($categorySlug, function ($queryBuilder) use ($categorySlug) {
                    $queryBuilder->whereHas('category', function ($categoryQuery) use ($categorySlug) {
                        $categoryQuery->where('slug', $categorySlug); // Match category slug
                    });
                })
                ->when($minPrice, function ($queryBuilder) use ($minPrice) {
                    $queryBuilder->where('product_price', '>=', $minPrice);
                })
                ->when($maxPrice, function ($queryBuilder) use ($maxPrice) {
                    $queryBuilder->where('product_price', '<=', $maxPrice);
                })
                ->when($status, function ($queryBuilder) use ($status) {
                    $queryBuilder->where('product_stocks', $status === 'active' ? '>' : '=', 0);
                })
                ->paginate(5);

            return response()->json($products);
        }

        return view('admins.adminproducts', compact('categories'));
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'product_description' => 'required|string',
            'product_price' => 'required|numeric|min:0',
            'product_stocks' => 'required|integer|min:0', // Validate stocks
            'category_id' => 'required|exists:categories,id',
        ]);

        // Handle the image logic
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');

            // Define the destination path in the public directory
            $destinationPath = public_path('assets/img');

            // Use the original file name
            $imageName = $image->getClientOriginalName();

            // Check if the file already exists
            if (!file_exists($destinationPath . '/' . $imageName)) {
                // Move the file to the destination path if it does not exist
                $image->move($destinationPath, $imageName);
            }

            // Store the filename in the database
            $validatedData['product_image'] = $imageName;
        }
        Log::info($validatedData);

        // Create the product
        $product = Product::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Product added successfully!',
            'product' => $product
        ]);
    }


    public function admincategories(Request $request)
    {
        if ($request->ajax()) {
            $query = Category::query();

            // Apply search filter if there's a search term
            if ($request->has('search') && !empty($request->search)) {
                $query->where('category_name', 'like', '%' . $request->search . '%');
            }

            $categories = $query->paginate(5);

            return response()->json([
                'data' => $categories->items(), // Paginated category data
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total(),
            ]);
        }

        return view("admins.admincategories");
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required|unique:categories,category_name|max:255',
            'category_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        DB::beginTransaction(); // ✅ Start Transaction

        try {
            $imageName = null;

            // Handle Image Upload
            if ($request->hasFile('category_image')) {
                $image = $request->file('category_image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('assets/img/'), $imageName);
            }

            // ✅ Insert data using DB instead of Eloquent
            $categoryId = DB::table('categories')->insertGetId([
                'category_name' => $request->category_name,
                'category_image' => $imageName,
                'slug' => Str::slug($request->category_name),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit(); // ✅ Commit Transaction

            return response()->json([
                'success' => true,
                'message' => 'Category added successfully!',
                'category' => [
                    'id' => $categoryId,
                    'category_name' => $request->category_name,
                    'category_image' => $imageName,
                    'slug' => Str::slug($request->category_name)
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to add category.',
                'error' => $e->getMessage()
            ], 500);
        }
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

    public function adminadministrators()
    {
        return view("admins.adminadministrators");
    }

    public function admincustomers()
    {
        return view("admins.admincustomers");
    }

    public function adminemployee()
    {
        return view("admins.adminemployee");
    }

    public function adminaudit()
    {
        return view("admins.adminaudit");
    }

    public function adminsalesreport()
    {
        return view("admins.adminsalesreport");
    }

    public function adminproductsreport()
    {
        return view("admins.adminproductsreport");
    }




    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();


        return redirect('/')->with('message', 'Logout Successful');
    }
}
