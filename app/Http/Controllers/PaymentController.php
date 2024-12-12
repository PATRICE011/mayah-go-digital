<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Orderdetails;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
class PaymentController extends Controller
{
    // Handle successful payment
    public function paymentSuccess(Request $request)
    {
        Log::info('Payment Success method hit');

        // Retrieve session data
        $sessionId = Session::get('session_id');
        $orderId = Session::get('order_id');

        if (!$sessionId || !$orderId) {
            return redirect()->route('/cart')->with('error', 'Session or Order not found.');
        }

        // Fetch session details from PayMongo API
        $response = Curl::to("https://api.paymongo.com/v1/checkout_sessions/$sessionId")
            ->withHeader('Content-Type: application/json')
            ->withHeader('accept: application/json')
            ->withHeader('Authorization: Basic ' . env('AUTH_PAY'))
            ->withOption('SSL_VERIFYPEER', false) // Disable SSL verification
            ->asJson()
            ->get();

        // Debug response to ensure API is working
        Log::info('PayMongo API Response: ' . json_encode($response));

        if (isset($response->data)) {
            $order = Order::where('id', $orderId)->where('status', 'pending')->first();

            if ($order) {
                // Check if order ID custom already exists
                $existingOrderDetails = Orderdetails::where('order_id', $orderId)->first();

                if ($existingOrderDetails) {
                    Log::info('Order ID Custom already exists, skipping generation.');
                } else {
                    // Generate unique order ID custom
                    $customOrderId = $this->generateUniqueOrderId();
                }

                // Extract payment details
                $paymentMethod = $response->data->attributes->payments[0]->attributes->source->type ?? null;
                $totalAmount = $response->data->attributes->payments[0]->attributes->amount / 100 ?? null; 

                Log::info('Payment Method: ' . $paymentMethod);
                Log::info('Total Amount: ' . $totalAmount);

                if (!$paymentMethod || !$totalAmount) {
                    return redirect()->back()->with('error', 'Payment details not found.');
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

                try {
                    // Create or update order details
                    Orderdetails::updateOrCreate(
                        ['order_id' => $order->id],
                        [
                            'order_id_custom' => $existingOrderDetails->order_id_custom ?? $customOrderId,
                            'payment_method' => $paymentMethod,
                            // 'total_amount' => $totalAmount,
                        ]
                    );
                    Log::info('Orderdetails saved successfully.');
                } catch (\Exception $e) {
                    Log::error('Error saving order details: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'Unable to save order details.');
                }

                // Delete cart items after successful payment
                $cart = Cart::where('user_id', $order->user_id)->first();
                if ($cart) {
                    $cart->items()->delete(); // Delete all items in the cart
                    $cart->delete(); // Delete the cart itself
                    Log::info('Cart and its items deleted successfully.');
                }

                // Clear session data
                Session::forget('session_id');
                Session::forget('order_id');

                // Redirect the user to the orders page
                return redirect('/cart')->with('message', 'Order placed successfully.');
            } else {
                return redirect('/post-error')->with('error', 'No matching pending order found.');
            }
        } else {
            Log::error('Failed to retrieve PayMongo session details', (array)$response);
            return redirect('/post-error')->with('error', 'Payment session retrieval failed.');
        }
    }



    // Create payment for testing purposes
    public function createPayment($orderId)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Find the specified order by order ID and ensure it's 'pending'
        $order = Order::where('id', $orderId)
            ->where('user_id', $user->id)
            ->where('status', 'pending') // Only process orders that are still pending
            ->first();

        if (!$order) {
            return redirect()->back()->with('error', 'No matching pending orders found.');
        }

        // Fetch the cart ID
        $cartId = DB::table('carts')->where('user_id', $user->id)->value('id');
        if (!$cartId) {
            return redirect()->back()->with('error', 'Cart not found.');
        }

        // Calculate total amount from the cart
        $totalAmount = DB::table('cart_items')
            ->where('cart_id', $cartId)
            ->select(DB::raw('SUM(price * quantity) as total_amount'))
            ->value('total_amount');

        if (!$totalAmount || $totalAmount <= 0) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        // Ensure the order has items
        if ($order->orderItems()->count() > 0) {
            // Generate a unique custom order ID
            $customOrderId = $this->generateUniqueOrderId();

            // Update or create the custom order ID and total amount in Orderdetails
            Orderdetails::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'order_id_custom' => $customOrderId,
                    'total_amount' => $totalAmount,
                ]
            );

            Log::info('Generated and saved custom order ID: ' . $customOrderId);

            // Prepare line items for the payment gateway (PayMongo)
            $lineItems = [];
            foreach ($order->orderItems as $orderItem) {
                $lineItems[] = [
                    'currency' => 'PHP',
                    'amount' => $orderItem->price * 100, // Convert to cents
                    'name' => $orderItem->product->product_name,
                    'quantity' => $orderItem->quantity,
                ];
            }

            // Prepare the request data for PayMongo
            $data = [
                'data' => [
                    'attributes' => [
                        'line_items' => $lineItems,
                        'payment_method_types' => ['gcash', 'paymaya'],
                        'success_url' => route('payment.success'),
                        'cancel_url' => route('payment.cancel'),
                    ],
                ],
            ];

            // Send the API request to PayMongo
            try {
                $response = Curl::to('https://api.paymongo.com/v1/checkout_sessions')
                    ->withHeader('Content-Type: application/json')
                    ->withHeader('accept: application/json')
                    ->withHeader('Authorization: Basic ' . env('AUTH_PAY'))
                    ->withOption('SSL_VERIFYPEER', false)
                    ->withData($data)
                    ->asJson()
                    ->post();

                if (isset($response->data)) {
                    Session::put('session_id', $response->data->id);
                    Session::put('order_id', $order->id);

                    return redirect()->to($response->data->attributes->checkout_url);
                } else {
                    Log::error('PayMongo API response error', (array)$response);
                    return redirect()->back()->with('error', 'Payment initiation failed. Please try again.');
                }
            } catch (\Exception $e) {
                Log::error('PayMongo API request failed', ['exception' => $e->getMessage()]);
                return redirect()->back()->with('error', 'An error occurred while initiating payment. Please try again.');
            }
        } else {
            return redirect()->back()->with('error', 'Your order has no items.');
        }
    }


    public function postSuccess()
    {
        return view("home.postsuccess");
    }

    public function postError()
    {
        return view("home.posterror");
    }

    // Generate unique 7-digit order ID
    private function generateUniqueOrderId()
    {
        do {
            $orderIdCustom = mt_rand(1000000, 9999999); // Generate a random 7-digit number
        } while (Orderdetails::where('order_id_custom', $orderIdCustom)->exists()); // Ensure it's unique

        return $orderIdCustom;
    }
    public function paymentCancel(Request $request)
    {
        Log::info('Payment Cancel method hit');

        // You can log or process any actions needed when payment is canceled
        // For now, we just redirect the user back to the cart or homepage

        return redirect('/cart')->with('message', 'Payment was canceled.');
    }
}
