@extends('admins.layout')

@section('title', 'Online Orders')

@section('content')
<main class="container section">
    <div id="preloader" style="display: none;">
        <div class="spinner"></div>
    </div>

    <div class="orders-container">
        <div class="header">
            <h2>Online Orders</h2>
            <div class="action-buttons">
                <button class="filter-btn">Filter</button>
                <button class="export-btn">Export</button>
            </div>
        </div>

        <table class="orders-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Payment Method</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>{{ $order->order_id }}</td>
                        <td>{{ $order->customer }}</td>
                        <td>{{ ucfirst($order->payment_method) }}</td>
                        <td>{{ number_format($order->amount, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->date)->format('h:i A, d-m-Y') }}</td>
                        <td>
                            <span class="status {{ strtolower($order->status) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            <button class="action-btn">
                                <a href="{{ route('admins.view', ['id' => $order->order_id]) }}">
                                    👁️
                                </a>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No orders found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <p class="footer">Showing {{ $orders->count() }} of {{ $orders->count() }} entries</p>
    </div>
</main>
@endsection
