<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

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

    // public function updateOrderStatus(Request $request, $id)
    // {
    //     try {
    //         $order = Order::findOrFail($id); // Fetch order by ID

    //         $validated = $request->validate([
    //             'status' => 'required|string|in:pending,confirmed,readyForPickup,completed,returned,refunded' // Correct the case to match HTML
    //         ]);

    //         $order->status = $validated['status']; // Update status
    //         $order->save(); // Save changes

    //         return response()->json(['success' => true, 'message' => 'Order status updated successfully.', 'newStatus' => $order->status]);
    //     } catch (\Exception $e) {
    //         return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
    //     }
    // }

    public function updateOrderStatus(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id); // Fetch order by ID

            $validated = $request->validate([
                'status' => 'required|string|in:pending,confirmed,readyForPickup,completed,returned,refunded'
            ]);

            $order->status = $validated['status']; // Update status
            $order->save(); // Save changes

            // Prepare SMS message based on status
            $message = $this->getSmsMessageByStatus($order->status, $order);

            // Assume $order has a 'mobile_number' field; adjust if different
            $mobileNumber = $order->user->mobile ?? null;

            if ($mobileNumber && $message) {
                $this->sendSmsNotification($mobileNumber, $message);
            }


            if ($mobileNumber && $message) {
                $this->sendSmsNotification($mobileNumber, $message);
            }

            return response()->json(['success' => true, 'message' => 'Order status updated successfully.', 'newStatus' => $order->status]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    private function getSmsMessageByStatus($status, $order)
    {
        switch ($status) {
            case 'pending':
                return "Hi! Your order #{$order->id} is now pending. We'll notify you once it's confirmed.";
            case 'confirmed':
                return "Good news! Your order #{$order->id} has been confirmed.";
            case 'readyForPickup':
                return "Your order #{$order->id} is ready for pickup. Please visit us soon.";
            case 'completed':
                return "Thank you! Your order #{$order->id} is completed.";
            case 'returned':
                return "Your order #{$order->id} has been returned. Please contact support for more info.";
            case 'refunded':
                return "Your order #{$order->id} has been refunded. Check your account for details.";
            default:
                return null;
        }
    }
    private function sendSmsNotification($mobileNumber, $message)
    {
        // Retrieve Semaphore credentials from the .env file
        $apiKey = 'b44a24f27a558fb5290688a7ab25aded';
        $senderName = 'MAYAHSTORE';
        $apiUrl = 'https://api.semaphore.co/api/v4/priority';
        $client = new Client();
        // Initialize Guzzle client with SSL verification disabled
        // $client = new Client([
        //     'verify' => false, 
        // ]);

        // try {

        //     $response = $client->post('https://api.semaphore.co/api/v4/priority', [
        //         'form_params' => [
        //             'apikey'     => $apiKey,
        //             'number'     => $mobileNumber,
        //             'message'    => $message,
        //             'sendername' => $senderName,
        //         ],
        //     ]);

        try {
            Log::info('Sending SMS request', [
                'endpoint' => $apiUrl,
                'payload' => [
                    'apikey' => $apiKey,
                    'number' => $mobileNumber,
                    'message' => $message,
                    'sender' => $senderName,
                ]
            ]);

            $response = $client->post($apiUrl, [
                'form_params' => [
                    'apikey' => $apiKey,
                    'number' => $mobileNumber,
                    'message' => $message,
                    'sender' => $senderName,
                ],
                'verify' => false,
            ]);

            $responseBody = json_decode($response->getBody(), true);
            Log::info('SMS response', [
                'status_code' => $response->getStatusCode(),
                'response_body' => $responseBody,
            ]);

            if ($response->getStatusCode() !== 200 || isset($responseBody['error'])) {
                Log::error('Semaphore API error', [
                    'status_code' => $response->getStatusCode(),
                    'response_body' => $responseBody,
                ]);
                throw new \Exception('Failed to send OTP. Please try again.');
            }

            return $responseBody;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            Log::error('Guzzle ClientException', [
                'message' => $e->getMessage(),
                'response' => $e->getResponse()->getBody()->getContents(),
            ]);
            throw new \Exception('Failed to send OTP due to a client error. Please try again.');
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('Guzzle RequestException', [
                'message' => $e->getMessage(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ]);
            throw new \Exception('Failed to send OTP due to a network error. Please try again.');
        } catch (\Exception $e) {
            Log::error('General Exception while sending OTP', [
                'message' => $e->getMessage(),
            ]);
            throw new \Exception('Failed to send OTP due to a system error. Please try again.');
        }
    }
}
