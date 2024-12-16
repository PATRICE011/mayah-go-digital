<div class="order-container">
    <!-- Go Back Button -->
    <button class="back-to-orders-btn" onclick="goToActiveTab()">
        <i class="ri-arrow-left-line"></i> Back to Orders
    </button>

    <!-- Order Header -->
    <div class="order-header">
        <h1>Thank You</h1>
        <p>Your order status is as follows:</p>
        <p><strong>Order ID: #{{ $order->order_id_custom }}</strong></p>
    </div>

    <!-- Progress Bar -->
    <div class="order-progress-bar">
        <!-- Step: Pending -->
        <div class="order-progress-step 
            {{ in_array($order->status, ['pending', 'confirmed', 'ready-for-pickup', 'completed']) ? 'completed' : '' }} 
            {{ $order->status == 'paid' ? 'active' : '' }}">
            <span>Pending</span>
        </div>

        <!-- Step: Confirmed -->
        <div class="order-progress-step 
            {{ in_array($order->status, ['confirmed', 'ready-for-pickup', 'completed']) ? 'completed' : '' }} 
            {{ $order->status == 'confirmed' ? 'active' : '' }}">
            <span>Confirmed</span>
        </div>

        <!-- Step: Ready for Pickup -->
        <div class="order-progress-step 
            {{ in_array($order->status, ['ready-for-pickup', 'completed']) ? 'completed' : '' }} 
            {{ $order->status == 'ready-for-pickup' ? 'active' : '' }}">
            <span>Ready for Pickup</span>
        </div>

        <!-- Step: Completed -->
        <div class="order-progress-step 
            {{ $order->status == 'completed' ? 'completed active' : '' }}">
            <span>Completed</span>
        </div>
    </div>

    <!-- Order Details -->
    <div class="order-details">
        <div class="order-card">
            <h4>Order Details</h4>
            <p>Order ID: <span class="order-highlight">#{{ $order->order_id_custom }}</span></p>
            <p>Order Date: <span class="order-highlight">{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y') }}</span></p>
            <p>Order Status: <span class="order-highlight">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span></p>
            <p>Payment Status: <span class="order-highlight">{{ ucfirst($order->payment_status ?? 'Unpaid') }}</span></p>
            <p>Payment Method: <span class="order-highlight">{{ ucfirst($order->payment_method ?? 'N/A') }}</span></p>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="order-summary">
        <h4>Order Summary</h4>
        <table>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
            </tr>

            @foreach ($orderItems as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td>₱ {{ number_format($item->price, 2) }}</td>
                <td>{{ $item->quantity }}</td>
            </tr>
            @endforeach

            <!-- Subtotal -->
            <tr>
                <td colspan="2">Subtotal</td>
                <td>₱ {{ number_format($orderItems->sum(fn($item) => $item->quantity * $item->price), 2) }}</td>
            </tr>

            <!-- Total -->
            <tr class="order-total">
                <td colspan="2">Total</td>
                <td>₱ {{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="form__btn">
        <a href="{{ route('order.invoice', ['orderId' => $order->order_id]) }}" target="_blank">
            <button class="btn btn--md">Invoice</button>
        </a>
    </div>
</div>