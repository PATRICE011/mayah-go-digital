<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Exports\SalesPosExport;
use Maatwebsite\Excel\Facades\Excel;

class PosController extends Controller
{
    /**
     * Admin POS Page
     */
    public function adminpos()
    {
        $categories = Category::all();
        $products = Product::paginate(6);
        return view('admins.adminpos', compact('categories', 'products'));
    }

    /**
     * Get All Categories
     */
    public function getCategories()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    /**
     * Get Products with Pagination and Optional Category Filtering
     */
    // Refactor to use custom pagination
    public function getProducts(Request $request)
    {
        $query = Product::query();
    
        // Category filter
        if ($request->has('category_id') && $request->category_id != 'all') {
            $category = Category::find($request->category_id);
            if (!$category) {
                return response()->json(['message' => 'Invalid category ID'], 400);
            }
            $query->where('category_id', $request->category_id);
        }
    
        // Search filter
        if ($request->has('search')) {
            $searchQuery = $request->search;
            $query->where('product_name', 'like', "%$searchQuery%")
                ->orWhere('product_description', 'like', "%$searchQuery%");
        }
    
        // Pagination logic
        $perPage = 6; // Number of products per page
        $page = $request->input('page', 1); // Current page
    
        $products = $query->paginate($perPage, ['*'], 'page', $page); // Paginate
    
        // Pagination information
        $pagination = [
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'per_page' => $perPage,
            'total' => $products->total(),
        ];
    
        return response()->json([
            'products' => $products->items(), // Return only the products for this page
            'pagination' => $pagination,
        ]);
    }
    
    /**
     * Add a Product to the Cart
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = Session::get('cart', []);

        // Check if the product is already in the cart
        if (isset($cart[$product->id])) {
            $currentCartQuantity = $cart[$product->id]['quantity'];

            // Check if adding one more would exceed available stock
            if ($currentCartQuantity + 1 > $product->product_stocks) {
                return response()->json([
                    'message' => 'Insufficient stock available.',
                    'current_stock' => $product->product_stocks,
                    'current_cart_quantity' => $currentCartQuantity
                ], 400);
            }

            // Increment quantity
            $cart[$product->id]['quantity']++;
            $cart[$product->id]['subtotal'] = $cart[$product->id]['quantity'] * $cart[$product->id]['price'];
        } else {
            // Adding new product to cart
            if ($product->product_stocks < 1) {
                return response()->json([
                    'message' => 'Insufficient stock available.',
                    'current_stock' => $product->product_stocks
                ], 400);
            }

            // Add the product to the cart
            $cart[$product->id] = [
                'name' => $product->product_name,
                'price' => $product->product_price,
                'quantity' => 1,
                'subtotal' => $product->product_price
            ];
        }

        // Save the cart in session
        Session::put('cart', $cart);

        return response()->json([
            'message' => 'Product added to cart successfully',
            'cart' => $cart
        ]);
    }

    // ProductController.php

    public function checkStock(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity'); // Get the quantity to check

        // Fetch the product from the database
        $product = Product::findOrFail($productId);

        // Check if the requested quantity is less than or equal to available stock
        $stockAvailable = $product->product_stocks >= $quantity;

        return response()->json([
            'stockAvailable' => $stockAvailable, // Return whether stock is available for the quantity
            'product_stocks' => $product->product_stocks // Send the available stock for feedback
        ]);
    }


    public function clear(Request $request)
    {
        Session::forget('cart');
        return response()->json(['message' => 'Cart cleared successfully']);
    }



    public function destroyPOS(Request $request, $productId)
    {
        // Validate that the product ID exists in the session's cart
        $cart = Session::get('cart', []);

        // Check if the product is in the cart
        if (!isset($cart[$productId])) {
            return response()->json(['message' => 'Item not found in the cart.'], 404);
        }

        // Remove the item from the cart
        unset($cart[$productId]);

        // Update the session with the modified cart
        Session::put('cart', $cart);

        return response()->json([
            'message' => 'Item removed from cart successfully.',
            'cart' => $cart
        ], 200);
    }

    /**
     * Get Current Cart Items
     */
    public function getCart()
    {
        $cart = Session::get('cart', []);
        $total = array_sum(array_column($cart, 'subtotal'));

        return response()->json([
            'cart' => $cart,
            'total' => $total,
        ]);
    }

    /**
     * Update Quantity of a Product in the Cart
     */
    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Session::get('cart', []);

        if (isset($cart[$request->product_id])) {
            $cart[$request->product_id]['quantity'] = $request->quantity;
            $cart[$request->product_id]['subtotal'] = $cart[$request->product_id]['quantity'] * $cart[$request->product_id]['price'];

            Session::put('cart', $cart);
        }

        return response()->json([
            'message' => 'Cart updated successfully',
            'cart' => $cart,
            'total' => array_sum(array_column($cart, 'subtotal')),
        ]);
    }

    /**
     * Checkout and Clear the Cart
     */
    public function checkout(Request $request)
    {
        // Check if the cart is empty
        if (!Session::has('cart') || empty(Session::get('cart'))) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        // Validate cash paid
        $request->validate([
            'cash_paid' => 'required|numeric|min:0',
        ]);

        // Get the cart from the session
        $cart = Session::get('cart');
        $totalAmount = array_sum(array_column($cart, 'subtotal'));

        // Check if cash paid is enough
        if ($request->cash_paid < $totalAmount) {
            return response()->json(['message' => 'Insufficient cash payment'], 400);
        }

        // Calculate change
        $change = $request->cash_paid - $totalAmount;

        // Start a transaction
        DB::beginTransaction();

        try {
            // Create a new POS order in the pos_orders table
            $orderNumber = Str::random(10); // Generate a random order number
            $posOrderId = DB::table('pos_orders')->insertGetId([
                'order_number' => $orderNumber, // Unique order number
                'user_id' => null, // For POS, the user is typically a guest, so leave null
                'cash_paid' => $request->cash_paid,
                'change' => $change,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Loop through the cart and save each item in the pos_order_items table
            foreach ($cart as $productId => $item) {
                // Check if the product exists and if stock is sufficient
                $product = DB::table('products')->where('id', $productId)->first();
                if (!$product || $product->product_stocks < $item['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'message' => "Insufficient stock for {$item['name']}",
                    ], 400);
                }

                // Decrease the product stock in the products table
                DB::table('products')->where('id', $productId)->decrement('product_stocks', $item['quantity']);

                // Insert the order item into the pos_order_items table
                DB::table('pos_order_items')->insert([
                    'pos_order_id' => $posOrderId, // Link this order item to the pos_order
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['subtotal'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Clear the cart after a successful checkout
            Session::forget('cart');

            // Commit the transaction
            DB::commit();

            return response()->json([
                'message' => 'Order placed successfully',
                'order_id' => $posOrderId,
                'change' => $change,
            ]);
        } catch (\Exception $e) {
            // Rollback in case of failure
            DB::rollBack();
            return response()->json(['message' => 'Something went wrong while placing the order'], 500);
        }
    }


    /**
     * Admin POS Report Page
     */
    public function adminposreport(Request $request)
    {
        // Initialize query for retrieving POS order items
        $query = DB::table('pos_order_items')
            ->join('products', 'pos_order_items.product_id', '=', 'products.id')
            ->join('pos_orders', 'pos_order_items.pos_order_id', '=', 'pos_orders.id')
            ->select('pos_order_items.id', 'products.product_name', 'pos_order_items.quantity', 'pos_order_items.price', 'pos_order_items.total', 'pos_order_items.created_at', 'pos_orders.order_number', DB::raw('IFNULL(pos_orders.user_id, "Guest") as customer'))
            ->orderBy('pos_order_items.created_at', 'desc');

        // Apply search filter if any search term is provided
        if ($searchQuery = $request->input('search')) {
            $query->where('products.product_name', 'like', "%$searchQuery%")
                ->orWhere('products.product_description', 'like', "%$searchQuery%");
        }

        // Apply date range filter if any dates are provided
        if ($fromDate = $request->input('fromDate')) {
            $query->whereDate('pos_order_items.created_at', '>=', $fromDate);
        }

        if ($toDate = $request->input('toDate')) {
            $query->whereDate('pos_order_items.created_at', '<=', $toDate);
        }

        // Get paginated results
        $salesReport = $query->paginate(10);

        // Modify report data if necessary using map()
        $salesReport->getCollection()->map(function ($item) {
            // Update unit price field and amount
            $item->unit_price = number_format($item->price, 2); // Format price
            $item->amount = number_format($item->total, 2); // Format total amount
            return $item;
        });

        return view('admins.adminposreport', [
            'salesReport' => $salesReport, // Pass the paginated report to the view
        ]);
    }


    public function exportPosReport(Request $request)
    {
        // Optional: Accept date filters or other parameters from the request
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Generate and download the POS order report
        return Excel::download(new SalesPosExport($fromDate, $toDate), 'pos-report.xlsx');
    }


    public function search(Request $request)
    {
        $searchQuery = $request->input('search', ''); // Get the search query (default to an empty string if not provided)
        $categoryId = $request->input('category_id', 'all'); // Category ID (default to 'all' if not provided)
        $page = $request->input('page', 1); // Page number (default to 1 if not provided)
    
        // Initialize the query builder
        $query = DB::table('products');
    
        // Filter by category if category is provided
        if ($categoryId != 'all') {
            $query->where('category_id', $categoryId);
        }
    
        // Search by product name, description, or any other field you want
        if ($searchQuery) {
            $query->where('product_name', 'like', '%' . $searchQuery . '%')
                ->orWhere('product_description', 'like', '%' . $searchQuery . '%');
        }
    
        // Get the total count of the filtered products
        $total = $query->count();
    
        // Paginate the results (6 products per page)
        $perPage = 6;
        $products = $query->skip(($page - 1) * $perPage)->take($perPage)->get();
    
        // Calculate pagination information
        $pagination = [
            'current_page' => $page,
            'last_page' => ceil($total / $perPage),
            'per_page' => $perPage,
            'total' => $total,
        ];
    
        // Return the products and pagination as a JSON response
        return response()->json([
            'products' => $products,
            'pagination' => $pagination
        ]);
    }
    
}
