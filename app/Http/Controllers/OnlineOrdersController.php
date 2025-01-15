<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OnlineOrdersController extends Controller
{
    //

    public function adminonlineorders(Request $request)
    {
        $search = $request->get('search', '');
        $pageSize = $request->get('pageSize', 10);
        $filterOrderID = $request->get('order_id', null);
        $filterDate = $request->get('date', null);
        $filterStatus = $request->get('status', null);
    
        // Fetch orders with filters and relationships
        $orders = Order::with(['orderdetails', 'user'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->when($filterOrderID, function ($query) use ($filterOrderID) {
                $query->whereHas('orderdetails', function ($q) use ($filterOrderID) {
                    $q->where('order_id_custom', $filterOrderID);
                });
            })
            ->when($filterDate, function ($query) use ($filterDate) {
                $query->whereDate('created_at', $filterDate);
            })
            ->when($filterStatus, function ($query) use ($filterStatus) {
                $query->where('status', $filterStatus);
            })
            ->paginate($pageSize);
    
        if ($request->ajax()) {
            return response()->json($orders);
        }
    
        return view('admins.adminonlineorders', compact('orders'));
    }
    


    public function getOrderDetails($id)
    {
        $order = Order::with(['orderItems.product', 'orderdetails', 'user'])
            ->findOrFail($id); // Fetch order with related models

        return response()->json($order);
    }

    public function updateOrderStatus(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id); // Fetch order by ID
    
            $validated = $request->validate([
                'status' => 'required|string|in:pending,confirmed,readyForPickup,completed' // Correct the case to match HTML
            ]);
    
            $order->status = $validated['status']; // Update status
            $order->save(); // Save changes
    
            return response()->json(['success' => true, 'message' => 'Order status updated successfully.', 'newStatus' => $order->status]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    
}
