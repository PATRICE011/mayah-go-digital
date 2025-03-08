<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Exports\StockReportExport;
use Maatwebsite\Excel\Facades\Excel;
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
    
    public function export(){
        return Excel::download(new StockReportExport, 'stocks-report.xlsx');
    }
}
