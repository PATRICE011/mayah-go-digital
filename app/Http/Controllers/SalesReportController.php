<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;

class SalesReportController extends Controller
{
    //

    public function adminSalesReport(Request $request)
    {
        $perPage = $request->input('per_page', 10); // Number of items per page
        $page = $request->input('page', 1); // Current page
        $search = $request->input('search', ''); // Search query
        $fromDate = $request->input('from_date'); // From date filter
        $toDate = $request->input('to_date'); // To date filter

        // Offset calculation for pagination
        $offset = ($page - 1) * $perPage;

        // Build the base query
        $query = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->leftJoin('users_area', 'orders.user_id', '=', 'users_area.id')
            ->select(
                'products.product_name',
                'order_items.quantity',
                'order_items.price as unit_price',
                DB::raw('order_items.quantity * order_items.price as total_amount'),
                'orders.created_at as date',
                'users_area.name as customer_name'
            )
            ->where('orders.status', 'paid'); // Filter by paid status

        // Apply search filter if provided
        if (!empty($search)) {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('products.product_name', 'LIKE', "%{$search}%")
                    ->orWhere('users_area.name', 'LIKE', "%{$search}%");
            });
        }

        // Apply date filter if provided
        if (!empty($fromDate) && !empty($toDate)) {
            $query->whereBetween('orders.created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        } elseif (!empty($fromDate)) {
            $query->where('orders.created_at', '>=', $fromDate . ' 00:00:00');
        } elseif (!empty($toDate)) {
            $query->where('orders.created_at', '<=', $toDate . ' 23:59:59');
        }

        // Get the total count for pagination
        $total = $query->count();

        // Fetch the paginated results
        $sales = $query->limit($perPage)
            ->offset($offset)
            ->get();

        // Prepare the paginated response manually
        $response = [
            'data' => $sales,
            'current_page' => $page,
            'per_page' => $perPage,
            'last_page' => ceil($total / $perPage),
            'total' => $total,
            'prev_page_url' => $page > 1 ? route('admins.adminsalesreport', ['page' => $page - 1, 'search' => $search, 'from_date' => $fromDate, 'to_date' => $toDate]) : null,
            'next_page_url' => $page < ceil($total / $perPage) ? route('admins.adminsalesreport', ['page' => $page + 1, 'search' => $search, 'from_date' => $fromDate, 'to_date' => $toDate]) : null,
        ];

        // Handle AJAX request
        if ($request->ajax()) {
            return response()->json($response);
        }

        // Return the view for non-AJAX requests
        return view('admins.adminsalesreport', compact('sales', 'response'));
    }

    public function exportSalesReport(Request $request)
    {
        return Excel::download(new SalesExport($request->from_date, $request->to_date, $request->search), 'sales-report.xlsx');
    }
}
