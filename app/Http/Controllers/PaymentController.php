<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Orderdetails;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
class PaymentController extends Controller
{
    public function paymentSuccess(Request $request)
{
    Log::info('Payment Success method hit');

    // Retrieve session data
    $sessionId = Session::get('session_id');
    $orderId = Session::get('order_id');

    if (!$sessionId || !$orderId) {
        return redirect()->route('/home')->with('error', 'Session or Order not found.');
    }

    // Fetch session details from PayMongo API
    $response = Curl::to("https://api.paymongo.com/v1/checkout_sessions/$sessionId")
        ->withHeader('Content-Type: application/json')
        ->withHeader('accept: application/json')
        ->withHeader('Authorization: Basic ' . env('AUTH_PAY'))
        ->asJson()
        ->get();

    // Debug response to ensure API is working
    Log::info('PayMongo API Response: ' . json_encode($response));

    if (isset($response->data)) {
        $order = Order::where('id', $orderId)->where('status', 'pending')->first();

        if ($order) {
            // Correctly extract the payment method type from the response
            $paymentMethod = $response->data->attributes->payments[0]->attributes->source->type ?? null;
            Log::info('Payment Method: ' . $paymentMethod);

            if (!$paymentMethod) {
                return redirect()->back()->with('error', 'Payment method not found.');
            }

            // Update the order status to 'paid'
            $order->status = 'paid';
            $order->save();
            // Subtract stock for each item in the order
            foreach ($order->orderItems as $item) {
                $product = $item->product;
                if ($product) {
                    $product->product_stocks -= $item->quantity;
                    if ($product->product_stocks < 0) {
                        return redirect()->back()->with('error', 'Insufficient stock for product: ' . $product->product_name);
                    }
                    $product->save();
                }
            }
            // Generate unique 7-digit order ID for order details
            $customOrderId = $this->generateUniqueOrderId();

            // Calculate the total amount from the order items
            $totalAmount = $order->orderItems->sum(fn($item) => $item->price * $item->quantity);

            try {
                // Create order details record
                Orderdetails::create([
                    'order_id' => $order->id,
                    'order_id_custom' => $customOrderId,
                    'payment_method' => $paymentMethod,
                    'total_amount' => $totalAmount,
                ]);
                Log::info('Orderdetails saved successfully.');
            } catch (\Exception $e) {
                Log::error('Error saving order details: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Unable to save order details.');
            }

            // Clear session data
            Session::forget('session_id');
            Session::forget('order_id');

            // Redirect the user to the orders page
            return redirect('/post-sucess')->with('success', 'Payment completed successfully.');
        } else {
            return redirect('/post-error')->with('error', 'No matching pending order found.');
        }
    } else {
        Log::error('Failed to retrieve PayMongo session details', (array)$response);
        return redirect('/post-error')->with('error', 'Payment session retrieval failed.');
    }
}


    
private function generateUniqueOrderId()
{
    do {
        $orderIdCustom = mt_rand(1000000, 9999999);  // Generate a random 7-digit number
    } while (Orderdetails::where('order_id_custom', $orderIdCustom)->exists());  // Ensure it's unique

    return $orderIdCustom;
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
                'amount'      => $orderItem->price  * 100, // Multiply by 100 for cents (price per item, not total amount)
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

public function postSuccess(){
    return view("home.postsuccess");

}
public function postError(){
    return view("home.posterror");

}

}
