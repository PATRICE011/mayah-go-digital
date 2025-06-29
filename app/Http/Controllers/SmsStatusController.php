<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SmsStatusController extends Controller
{
    //


    // ==== STATUS CONFIRMED =====

    public function confirmOrder(Order $order)
    {
        $order->update(['status' => 'confirmed']);

        // Define the customer mobile number and message
        $mobileNumber = $order->user->mobile;
        $message = "Hello, {$order->user->name}! Your order with ID #{$order->orderDetail->order_id_custom} has been confirmed. Thank you for shopping with us!";

        // Send SMS notification
        $this->sendSmsNotification($mobileNumber, $message);

        // Redirect back with a success message
        return redirect()->back()->with('message', 'Order has been confirmed and SMS notification sent.');
    }
    // ===== STATUS REJECTED =======
    public function rejectOrder(Order $order)
    {
        $order->update(['status' => 'rejected']);

        $mobileNumber = $order->user->mobile;
        $message = "Hello, {$order->user->name}! Your order with ID #{$order->orderDetail->order_id_custom} has been rejected. We apologize for any inconvenience. Thank you for choosing us.";

        // Send SMS notification
        $this->sendSmsNotification($mobileNumber, $message);
        // Redirect back with a success message
        return redirect()->back()->with('message', 'Order has been rejected and SMS notification sent.');
    }
    // ==== STATUS READY TO PICK UP ====
    public function readyOrder(Order $order)
    {
        // Update order status to 'ready for pickup'
        $order->update(['status' => 'ready-for-pickup']);

        // Get the user's mobile number
        $mobileNumber = $order->user->mobile;

        // Create the ready for pickup message
        $message = "Hello, {$order->user->name}! Your order with ID #{$order->orderDetail->order_id_custom} is now ready for pickup. Thank you for choosing us!";

        // Send SMS notification
        $this->sendSmsNotification($mobileNumber, $message);

        // Redirect back with a success message
        return redirect()->back()->with('message', 'Order marked as ready for pickup and SMS notification sent.');
    }
    // ======= STATUS COMPLETED =======
    public function completeOrder(Order $order)
    {
        $order->update(['status' => 'completed']);

        return redirect()->back()->with('message', 'Order marked as completed.');
    }

    // ===== STATUS REFUNDED ======
    public function refundOrder(Order $order)
    {
        $order->update(['status' => 'refunded']);

        return redirect()->back()->with('message', 'Order marked as refunded.');
    }

    // Helper function to send SMS via Semaphore


    private function sendSmsNotification($mobileNumber, $message)
    {
        // Retrieve Semaphore credentials from the .env file
        $apiKey = 'b44a24f27a558fb5290688a7ab25aded';
        $senderName = 'MAYAHSTORE';

        // Initialize Guzzle client with SSL verification disabled
        $client = new Client([
            'verify' => false, // Bypass SSL certificate verification
        ]);

        try {
            // Send SMS using the Semaphore API
            $response = $client->post('https://api.semaphore.co/api/v4/priority', [
                'form_params' => [
                    'apikey'     => $apiKey,
                    'number'     => $mobileNumber,
                    'message'    => $message,
                    'sendername' => $senderName,
                ],
            ]);

            // Check if the request was successful
            if ($response->getStatusCode() === 200) {
                Log::info('SMS sent successfully to ' . $mobileNumber);
            } else {
                Log::error('Failed to send SMS. Response: ' . $response->getBody());
            }
        } catch (\Exception $e) {
            // Log any exceptions that occur
            Log::error('SMS sending failed: ' . $e->getMessage());
        }
    }
}
