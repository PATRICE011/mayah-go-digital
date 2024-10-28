@extends('admins.layout')
@section('title', 'Online Orders')
@section('content')

<div class="orders-wrapper">
    <main class="container section">
        <div class="orders__container mt-4">
            <h1 class="orders__title">Orders Management</h1>
            <table class="table mt-3">
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
                        <td>{{ $order->orderDetail->order_id_custom }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ ucfirst($order->orderDetail->payment_method) }}</td>
                        <td>₱ {{ number_format($order->orderDetail->total_amount, 2) }}</td>
                        <td>{{ $order->created_at->format('h:i A, d-m-Y') }}</td>
                        
                        <td>
                            <span class="status {{ strtolower($order->status) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>

                        <td>
                            <button class="action-btn">
                                <a href="{{ route('admins.view', ['id' => $order->orderDetail->order_id_custom]) }}" class="action-btn-icon">
                                    <i class="ri-briefcase-line"></i>
                                </a>

                                <a href="#" class="action-btn-icon">
                                    <i class="ri-inbox-archive-line"></i>
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

            <p class="footer">
                Showing {{ $orders->count() }} of {{ $orders->count() }} entries
            </p>
        </div>
    </main>
</div>
@endsection
