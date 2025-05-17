<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Exports\StockReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class StockController extends Controller
{
    //
    public function index(Request $request)
    {
        // Fetch Stock In movements with pagination and search
        $movement_in = $this->searchStockMovements($request->input('searchIn'), 'in');

        // Fetch Stock Out movements with pagination and search
        $movement_out = $this->searchStockMovements($request->input('searchOut'), 'out');

        // Fetch Current Stock with pagination and search
        $current_stock = $this->searchCurrentStock($request->input('searchStock'));

        return view('admins.stocks_report', compact('movement_in', 'movement_out', 'current_stock'));
    }

    private function searchStockMovements($searchTerm, $type)
    {
        $dateAlias = $type === 'in' ? 'stock_in_date' : 'stock_out_date';
        $quantityAlias = $type === 'in' ? 'stock_in_quantity' : 'stock_out_quantity';

        return DB::table('stock_movements')
            ->join('products', 'stock_movements.product_id', '=', 'products.id')
            ->select(
                'products.product_name',
                DB::raw('MAX(stock_movements.created_at) as ' . $dateAlias), // Dynamic date alias
                DB::raw('SUM(stock_movements.quantity) as ' . $quantityAlias) // Dynamic quantity alias
            )
            ->where('stock_movements.type', $type)
            ->when($searchTerm, function ($query) use ($searchTerm) {
                $query->where('products.product_name', 'like', "%{$searchTerm}%");
            })
            ->groupBy('products.product_name')
            ->paginate(10);
    }

    private function searchCurrentStock($searchTerm)
    {
        return DB::table('products')
            ->select('product_name', 'product_stocks as balance_quantity')
            ->when($searchTerm, function ($query) use ($searchTerm) {
                $query->where('product_name', 'like', "%{$searchTerm}%");
            })
            ->paginate(10);
    }

    private function getTimestampedFilename($baseFilename)
    {
        $currentDateTime = Carbon::now()->format('Y-m-d_H-i-s');
        return $baseFilename . '_' . $currentDateTime . '.xlsx';
    }

    public function export()
    {
        $fileName = $this->getTimestampedFilename('stocks-report');

        return Excel::download(
            new StockReportExport,
            $fileName
        );
    }


    // revised reports
    public function stock_in_report(Request $request)
    {
        // stock in
        // Get filters from request
        $search = $request->input('search');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');

        // Query using Query Builder for "in" stock
        $query = DB::table('stock_movements')
            ->join('products', 'stock_movements.product_id', '=', 'products.id') // Join on product_id from products table
            ->join('categories', 'products.category_id', '=', 'categories.id') // Join categories table
            ->select(
                'products.product_id',  // Fetch product_id from the products table for display
                'products.product_name',
                'products.product_raw_price', // Use product_raw_price instead of product_price
                'products.category_id',
                'categories.category_name', // Select category_name from categories table
                'stock_movements.created_at', // Get the created_at from stock_movements
                'products.updated_at as last_restock_date',
                DB::raw('SUM(CASE WHEN stock_movements.type = "in" THEN stock_movements.quantity ELSE 0 END) as in_quantity'),
                'stock_movements.created_at as stock_in_date'
            )
            ->groupBy(
                'products.product_id',  // Group by product_id from the products table
                'products.product_name',
                'products.product_raw_price',
                'products.category_id',
                'categories.category_name',
                'stock_movements.created_at',
                'products.updated_at'
            )
            ->where('stock_movements.type', 'in') // Filtering only "stock-in" movements
            ->havingRaw('SUM(CASE WHEN stock_movements.type = "in" THEN stock_movements.quantity ELSE 0 END) > 0'); // Exclude rows where in_quantity is 0

        // Apply search filter for product ID, product name, and category name
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('products.product_id', 'like', "%$search%")
                    ->orWhere('products.product_name', 'like', "%$search%")
                    ->orWhere('categories.category_name', 'like', "%$search%"); // Search by category name
            });
        }

        // Apply date range filter
        if ($fromDate && $toDate) {
            $query->whereBetween('stock_movements.created_at', [$fromDate, $toDate]);
        }

        // Paginate results (10 items per page for this example)
        $stockInReports = $query->paginate(10);

        return view('admins.stocks.in_report', compact('stockInReports', 'search', 'fromDate', 'toDate'));
    }


    public function stock_out_report(Request $request)
    {
        // stock out
        // Get filters from request
        $search = $request->input('search');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');

        // Query using Query Builder
        $query = DB::table('stock_movements')
            ->join('products', 'stock_movements.product_id', '=', 'products.id') // Join on product_id from products table
            ->join('categories', 'products.category_id', '=', 'categories.id') // Join categories table
            ->select(
                'products.product_id',  // Fetch product_id from the products table for display
                'products.product_name',
                'products.product_raw_price', // Use product_raw_price instead of product_price
                'products.category_id',
                'categories.category_name', // Select category_name from categories table
                'stock_movements.created_at', // Get the created_at from stock_movements
                'products.updated_at as last_restock_date',
                DB::raw('SUM(CASE WHEN stock_movements.type = "out" THEN stock_movements.quantity ELSE 0 END) as out_quantity'),
                'stock_movements.created_at as stock_out_date'
            )
            ->groupBy(
                'products.product_id',  // Group by product_id from the products table
                'products.product_name',
                'products.product_raw_price',
                'products.category_id',
                'categories.category_name',
                'stock_movements.created_at',
                'products.updated_at'
            )
            ->where('stock_movements.type', 'out') // Filtering only "stock-out" movements
            ->havingRaw('SUM(CASE WHEN stock_movements.type = "out" THEN stock_movements.quantity ELSE 0 END) > 0'); // Exclude rows where out_quantity is 0

        // Apply search filter for product ID, product name, and category name
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('products.product_id', 'like', "%$search%")
                    ->orWhere('products.product_name', 'like', "%$search%")
                    ->orWhere('categories.category_name', 'like', "%$search%"); // Search by category name
            });
        }

        // Apply date range filter
        if ($fromDate && $toDate) {
            $query->whereBetween('stock_movements.created_at', [$fromDate, $toDate]);
        }

        // Paginate results (10 items per page for this example)
        $stockInReports = $query->paginate(10);

        return view('admins.stocks.out_report', compact('stockInReports', 'search', 'fromDate', 'toDate'));
    }


    public function inventory_report()
    {
        // stock in

        return view('admins.stocks.inventory');
    }
}
