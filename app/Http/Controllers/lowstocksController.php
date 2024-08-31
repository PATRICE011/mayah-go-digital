<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class lowstocksController extends Controller
{
    //low stocks product name, available left
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('SEMAPHORE_API_KEY'); 
    }

    public function checkStock()
    {
        $threshold = config('inventory.low_stock_threshold'); // Fetch from config file
        $lowStockProducts = Product::where('product_stocks', '<=', $threshold)->get();

        foreach ($lowStockProducts as $product) {
            $message = "Low stock alert: The stock for {$product->name} is low.";
            $phoneNumber = '639127919278'; // Replace with the recipient's phone number

            // Send SMS notification
            $this->sendSms($phoneNumber, $message);
        }

        return response()->json(['message' => 'Low stock notifications sent.']);
    }

    protected function sendSms($phoneNumber, $message)
    {
        try {
            $response = $this->client->post('SEMAPHORE_API_URL', [
                'json' => [
                    'apikey' => $this->apiKey,
                    'number' => $phoneNumber,
                    'message' => $message,
                    'sendername' => 'SEMAPHORE_SENDER_NAME', // Optional: Replace with your sender name
                ]
            ]);

            $responseBody = $response->getBody()->getContents();
            return $responseBody;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
