@extends('admins.layout')
@section('title', 'POS Orders')
@section('content')

<div class="orders-wrapper">
    <main class="container section">
        <div class="orders__container mt-4">
            <h1 class="orders__title">POS Orders</h1>
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
                    <tr>
                        <td>12345</td>
                        <td>Anthony</td>
                        <td>GCash</td>
                        <td>₱1,000</td>
                        <td>Nov 21, 2024</td>
                        
                        <td>
                            <span>
                                Confirmed
                            </span>
                        </td>

                        <td>
                            <button class="action-btn">
                                <a href="{{ route('admins.viewposOrders')}}" class="action-btn-icon">
                                    <i class="ri-briefcase-line"></i>
                                </a>

                                <a href="#" class="action-btn-icon">
                                    <i class="ri-inbox-archive-line"></i>
                                </a>
                            </button>
                        </td>
                    </tr>
                        <tr>
                            <td colspan="7" class="text-center">No orders found</td>
                        </tr>
                </tbody>
            </table>

            <p class="footer">
                Showing 1 of 1 entries
            </p>
        </div>
    </main>
</div>
@endsection
