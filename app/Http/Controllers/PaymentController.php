<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
class PaymentController extends Controller
{
    public function paymentSuccess(Request $request)
{
     // Retrieve the session and order ID
     $sessionId = Session::get('session_id');
     $orderId = Session::get('order_id');
 
     // Find the order
     $order = Order::where('id', $orderId)->where('status', 'pending')->first();
 
     if ($order) {
         // Optionally verify the payment session with PayMongo API
         // Uncomment and implement if you need to verify the session
 
         // Update the order status to 'paid'
         $order->status = 'paid';
         $order->save();
 
         // Clear session data
         Session::forget('session_id');
         Session::forget('order_id');
 
         // Redirect to the order confirmation or success page
         return redirect()->route('home.myorders')->with('success', 'Payment completed successfully.');
     } else {
         return redirect()->route('home.myorders')->with('error', 'No matching pending order found.');
     }
}

    public function createPaymentTest($orderId)
    {
        // Get the authenticated user
        $user = Auth::user();
    
        // Find the specified order by order ID
        $order = Order::where('id', $orderId)
            ->where('user_id', $user->id)
            ->where('status', 'pending') // Only pending orders
            ->first();
    
        // Check if the order exists and has items
        if ($order && $order->orderItems()->count() > 0) {
            // Initialize variables for total amount and line items
            $lineItems = [];
            $totalAmount = 0;
    
            foreach ($order->orderItems as $orderItem) {
                $itemTotalPrice = $orderItem->price * $orderItem->quantity; // Calculate total price for each item
                $totalAmount += $itemTotalPrice; // Accumulate total amount
    
                // Add to line items array
                $lineItems[] = [
                    'currency'    => 'PHP',
                    'amount'      => $itemTotalPrice * 100, // Multiply by 100 for cents
                    'name'        => $orderItem->product->product_name, // Product name
                    'quantity'    => $orderItem->quantity,
                ];
            }
    
            // Prepare data for PayMongo API request
            $data = [
                'data' => [
                    'attributes' => [
                        'line_items' => $lineItems,
                        'payment_method_types' => ['gcash', 'paymaya'],
                        'success_url' => route('payment.success'),
                        // 'cancel_url' => route('payment.cancel'),
                    ],
                ]
            ];
    
            // Make the API request to PayMongo
            $response = Curl::to('https://api.paymongo.com/v1/checkout_sessions')
                ->withHeader('Content-Type: application/json')
                ->withHeader('accept: application/json')
                ->withHeader('Authorization: Basic ' . env('AUTH_PAY'))
                ->withData($data)
                ->asJson()
                ->post();
    
            // Debugging: Check the response
            if (isset($response->data)) {
                // Store the session ID and order ID in the session
                Session::put('session_id', $response->data->id);
                Session::put('order_id', $order->id);
    
                // Redirect to the checkout URL
                return redirect()->to($response->data->attributes->checkout_url);
            } else {
                // Log any error from the response
                Log::error('PayMongo API response', (array)$response);
    
                return redirect()->back()->with('error', 'Payment initiation failed. Please try again.');
            }
        } else {
            return redirect()->back()->with('error', 'No pending orders found.');
        }
    }
}
