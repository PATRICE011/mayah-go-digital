<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductReportController extends Controller
{
    //

    public function adminproductsreport(Request $request)
    {
        $perPage = $request->input('per_page', 10); // Number of items per page (default: 10)
        $page = $request->input('page', 1); // Current page
        $search = $request->input('search', ''); // Search query

        // Fetch paginated sales data with search functionality
        $sales = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.product_name',
                'order_items.quantity',
                'order_items.price'
            )
            ->when($search, function ($query, $search) {
                return $query->where('products.product_name', 'LIKE', "%{$search}%");
            })
            ->paginate($perPage, ['*'], 'page', $page);

        // Check if the request is AJAX
        if ($request->ajax()) {
            return response()->json($sales);
        }

        // Return the view with initial data for non-AJAX requests
        return view('admins.adminproductsreport', compact('sales'));
    }

    public function printProductReport(Request $request)
    {
        $search = $request->input('search', ''); // Optional search term

        // Fetch product report data
        $sales = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.product_name',
                'order_items.quantity',
                'order_items.price'
            )
            ->when($search, function ($query, $search) {
                return $query->where('products.product_name', 'LIKE', "%{$search}%");
            })
            ->get();

        // Return the print-friendly view
        return view('admins.export-product-report', compact('sales', 'search'));
    }
}
