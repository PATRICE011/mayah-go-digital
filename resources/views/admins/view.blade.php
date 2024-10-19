@extends('admins.layout')

@section('title', 'Order Details')

@section('content')
<main class="container section">
    <h2>Order Details</h2>

    <div class="order-container">
        <div class="order-header">
            <div class="order-details">
                <h1>Order ID: #{{ $order->orderDetail->order_id_custom }}</h1>
                <div class="status">
                    <span class="badge {{ strtolower($order->status) }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>

            <div class="order-actions">
                <button class="reject-btn">
                    <i class="fa fa-times"></i> Reject
                </button>
                <form action="{{ route('orders.confirm', $order->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="accept-btn">
                        <i class="fa fa-check"></i> Accept
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="SECOND">
        <div class="order-details-container">
            <h2>Order Items</h2>
            <ul class="order-items-list">
                @foreach($order->orderItems as $item)
                    <li class="order-item">
                        <div class="item-quantity">
                            <span>{{ $item->quantity }}</span>
                        </div>
                        <img src="{{ asset('assets/img/' . $item->product->product_image) }}" 
                             alt="{{ $item->product->product_name }}">
                        <div class="item-info">
                            <p class="item-name">{{ $item->product->product_name }}</p>
                            <p class="item-price">₱ {{ number_format($item->price, 2) }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="idk">
            <div class="order-summary-container">
                <table class="summary-table">
                    <tr>
                        <td>Subtotal</td>
                        <td class="order-price">
                            ₱ {{ number_format($order->orderItems->sum(fn($item) => $item->quantity * $item->price), 2) }}
                        </td>
                    </tr>

                    <tr>
                        <td>Discount</td>
                        <td class="order-price">₱ 0.00</td>
                    </tr>

                    <tr class="total-row">
                        <td><strong>Total</strong></td>
                        <td class="order-price"><strong>₱ {{ number_format($order->orderDetail->total_amount, 2) }}</strong></td>
                    </tr>
                </table>
            </div>

            <div class="billing-address-container">
                <h2>Customer Information</h2>
                <div class="billing-details">
                    <div class="contact-info">
                        <p>{{ $order->user->name }}</p>
                        <p><i class="fa fa-phone"></i> {{ $order->user->mobile }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@if (Session::has('message'))
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
        };

        toastr.success("{{ Session::get('message') }}");
    </script>
    @endif
@endsection
