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
    // Helper function to send SMS via Semaphore
    private function sendSmsNotification($mobileNumber, $message)
    {
        // Retrieve Semaphore credentials from the .env file
        $apiKey = env('SEMAPHORE_API_KEY');
        $senderName = env('SEMAPHORE_SENDER_NAME');
    
        // Initialize Guzzle client
        $client = new Client();
    
        try {
            // Send SMS using the Semaphore API
            $response = $client->post('https://api.semaphore.co/api/v4/messages', [
                'form_params' => [
                    'apikey' => $apiKey,
                    'number' => $mobileNumber,
                    'message' => $message,
                    'sendername' => $senderName,
                ],
            ]);
    
            // Check if the request was successful
            if ($response->getStatusCode() !== 200) {
                Log::error('Failed to send SMS. Response: ' . $response->getBody());
            }
        } catch (\Exception $e) {
            // Log any exceptions that occur
            Log::error('SMS sending failed: ' . $e->getMessage());
        }
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
    // ===== STATUS REFUNDED ======
}
