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
    public function getProducts(Request $request)
    {
        $query = Product::query();

        if ($request->has('category_id') && $request->category_id != 'all') {
            $category = Category::find($request->category_id);
            if (!$category) {
                return response()->json(['message' => 'Invalid category ID'], 400);
            }
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(6);

        return response()->json([
            'products' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
            ],
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

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += 1;
            $cart[$product->id]['subtotal'] = $cart[$product->id]['quantity'] * $cart[$product->id]['price'];
        } else {
            $cart[$product->id] = [
                'name' => $product->product_name,
                'price' => $product->product_price,
                'quantity' => 1,
                'subtotal' => $product->product_price,
            ];
        }

        Session::put('cart', $cart);

        return response()->json(['message' => 'Product added to cart successfully', 'cart' => $cart]);
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
        if (!Session::has('cart') || empty(Session::get('cart'))) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }
    
        $request->validate([
            'cash_paid' => 'required|numeric|min:0',
        ]);
    
        $cart = Session::get('cart');
        $totalAmount = array_sum(array_column($cart, 'subtotal'));
    
        if ($request->cash_paid < $totalAmount) {
            return response()->json(['message' => 'Insufficient cash payment'], 400);
        }
    
        $change = $request->cash_paid - $totalAmount;
    
        DB::beginTransaction();
    
        try {
            // Save the order
            $order = \App\Models\PosOrder::create([
                'order_number' => Str::random(10), // Unique order number
                'user_id' => Auth::id(), // Logged-in user ID
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'total_amount' => $totalAmount,
                'cash_paid' => $request->cash_paid,
                'change' => $change,
                'status' => 'completed',
            ]);
    
            // Save each cart item as an order item and update product stocks
            foreach ($cart as $productId => $item) {
                // Reduce product stock
                $product = \App\Models\Product::find($productId);
    
                if ($product->product_stocks < $item['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'message' => "Insufficient stock for {$product->product_name}",
                    ], 400);
                }
    
                $product->decrement('product_stocks', $item['quantity']);
    
                // Save the order item
                \App\Models\PosOrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['subtotal'],
                ]);
            }
    
            // Clear the cart after successful checkout
            Session::forget('cart');
    
            DB::commit();
    
            return response()->json([
                'message' => 'Order placed successfully',
                'order_id' => $order->id,
                'change' => $change,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Something went wrong while placing the order'], 500);
        }
    }
    /**
     * Admin POS Report Page
     */
    public function adminposreport(Request $request)
    {
        // Fetch and transform data before pagination
        $salesReport = \App\Models\PosOrderItem::with(['product', 'order.user']) // Load relationships
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_name' => $item->product->product_name ?? 'Unknown',
                    'quantity' => $item->quantity,
                    'unit_price' => number_format($item->price, 2),
                    'amount' => number_format($item->total, 2),
                    'date' => $item->created_at->format('Y-m-d H:i'),
                    'customer' => $item->order->user->name ?? 'Guest',
                ];
            });
    
        // Manually paginate the transformed data
        $perPage = 6; // Items per page
        $currentPage = $request->input('page', 1); // Current page or default to 1
        $paginatedReport = new \Illuminate\Pagination\LengthAwarePaginator(
            $salesReport->slice(($currentPage - 1) * $perPage, $perPage), // Slice the collection
            $salesReport->count(), // Total items
            $perPage, // Items per page
            $currentPage, // Current page
            ['path' => $request->url(), 'query' => $request->query()] // Append query parameters
        );
    
        return view('admins.adminposreport', [
            'salesReport' => $paginatedReport, // Pass the paginated object
        ]);
    }
    
    public function exportPosReport(Request $request)
    {
        // Optional: Accept date filters or other parameters from the request
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
    
        // Generate and download the report
        return Excel::download(new SalesPosExport($fromDate, $toDate), 'pos-report.xlsx');
    }
    
}
