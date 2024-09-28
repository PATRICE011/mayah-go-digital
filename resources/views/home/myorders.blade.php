<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <!-- Latest Bootstrap CSS (Bootstrap 5) -->
      <!-- ====== toastr ========-->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .order-wrapper {
            border: 2px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 20px;
            margin-bottom: 30px;
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
        }

        .order-number {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 15px;
            color: #343a40;
        }

        .order-card {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 15px;
            margin-bottom: 20px;
        }

        .order-image {
            max-width: 100px;
            height: auto;
            border-radius: 0.375rem;
        }

        .order-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            position: relative;
        }

        .order-info {
            flex-grow: 1;
            margin-left: 20px;
        }

        .amount-payable {
            margin-bottom: 10px;
            font-weight: bold;
        }

        .order-actions {
            margin-top: 15px;
            text-align: right;
        }

        .order-price {
            font-size: 1rem;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2>My Orders</h2>

    @if($orders->isEmpty())
        <p>No orders found.</p>
    @else
        <!-- Order Tabs -->
        <ul class="nav nav-tabs" id="orderTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="toPay-tab" data-bs-toggle="tab" href="#toPay" role="tab" aria-controls="toPay" aria-selected="true">To Pay</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="readyForPickup-tab" data-bs-toggle="tab" href="#readyForPickup" role="tab" aria-controls="readyForPickup" aria-selected="false">Ready for Pickup</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="completed-tab" data-bs-toggle="tab" href="#completed" role="tab" aria-controls="completed" aria-selected="false">Completed</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="cancelled-tab" data-bs-toggle="tab" href="#cancelled" role="tab" aria-controls="cancelled" aria-selected="false">Cancelled</a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-3" id="orderTabsContent">
            @foreach ($orders as $order)
                <div class="order-wrapper">
                    <div class="order-number">Order #{{ $order->id }}</div>

                    @foreach ($order->orderItems as $item)
                        <div class="order-card">
                            <div class="d-flex align-items-start position-relative">
                                <!-- Product Image -->
                                <img src="{{ asset('assets/img/' . $item->product->product_image ) }}" class="order-image" alt="Product Image">
                                <!-- Product Details -->
                                <div class="order-info">
                                    <h5>{{ $item->product->product_name }}</h5>
                                    <p><strong>Category:</strong> {{ $item->product->category->category_name }}</p>
                                    <p><strong>Quantity:</strong> {{ $item->quantity }}</p>
                                    <p class="order-price"><strong>Price per item:</strong> ₱ {{ $item->price }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Amount Payable -->
                    <div class="order-actions">
                        <p class="amount-payable">Amount Payable: ₱ {{ $order->total_amount }}</p>
                        <a href="{{ route('cart.pay', $order->id) }}" class="btn btn-primary">Pay Now</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

   
</div>

<!-- Latest Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
