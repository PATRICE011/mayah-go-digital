<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Exports\StockReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;

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

    public function export()
    {
        return Excel::download(new StockReportExport, 'stocks-report.xlsx');
    }


    // revised reports

    public function stock_in_report(Request $request)
    {
        // Get filters from request
        $search = $request->input('search');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');

        // Query using Query Builder
        $query = DB::table('stock_movements')
            ->join('products', 'stock_movements.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id') // Join categories table
            ->select(
                'stock_movements.*',
                'products.product_id',
                'products.product_name',
                'products.product_stocks',
                'products.product_price',
                'products.category_id',
                'categories.category_name', // Select category_name from categories table
                'products.updated_at as last_restock_date'
            )
            ->where('stock_movements.type', 'in'); // Filtering only "stock-in" movements

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


    public function stock_out_report()
    {
        // stock out

        return view('admins.stocks.out_report');
    }

    public function inventory_report()
    {
        // stock in

        return view('admins.stocks.inventory');
    }
}
