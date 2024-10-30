@extends('admins.layout')
@section('title', 'Order Details')
<!-- @section('content') -->

<main class="container-xl my-4 section">
    <div class="row justify-content-center">
        <div class="col-12">
            <h1 class="display-6">Order Details</h1>

            <!-- Show this card only if status is 'paid' -->
            @if (strtolower($order->status) === 'paid')
            <div class="card mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h4">Order ID: #{{ $order->orderDetail->order_id_custom }}</h2>
                        <span class="badge bg-{{ strtolower($order->status) }}" id="statusBadge">{{ ucfirst($order->status) }}</span>
                    </div>

                    <div>
                        <!-- Reject button with an onclick event -->
                        <form action="{{route('orders.reject',  $order->id)}}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-danger me-2" id="rejectButton" onclick="updateStatus('Rejected')"><i class="fa fa-times"></i> Reject</button>
                        </form>

                        <form action="{{ route('orders.confirm', $order->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Accept</button>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            @if (strtolower($order->status) === 'rejected')
            <div class="card mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h4">Order ID: #{{ $order->orderDetail->order_id_custom }}</h2>
                        <span class="badge bg-danger bg-{{ strtolower($order->status) }}" id="statusBadge">{{ ucfirst($order->status) }}</span>
                    </div>

                </div>
            </div>
            @endif

            <!-- Show this card only if status is 'confirmed' -->
            @if (strtolower($order->status) === 'confirmed')
            <div class="card mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h4">Order ID: #{{ $order->orderDetail->order_id_custom }}</h2>
                        <span class="badge bg-{{ strtolower($order->status) }}" id="statusBadge">{{ ucfirst($order->status) }}</span>
                    </div>

                    <div class="btn-group">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="statusButton" data-bs-toggle="dropdown" aria-expanded="false">
                            Status
                        </button>

                        <ul class="dropdown-menu">
                            <li>
                                <form action="{{ route('orders.ready', $order->id) }}" method="POST" id="readyForm-{{ $order->id }}" style="display: none;">
                                    @csrf
                                    <input type="hidden" name="status" value="Ready For Pickup">
                                </form>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('readyForm-{{ $order->id }}').submit();">
                                    Ready For Pickup
                                </a>
                            </li>

                            <li>
                                <form action="{{route('orders.complete', $order->id)}}" method="POST" id="completeForm-{{ $order->id }}" style="display: none;">
                                    @csrf
                                    <input type="hidden" name="status" value="Completed">
                                </form>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('completeForm-{{ $order->id }}').submit();">
                                    Completed
                                </a>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
            @endif
            
            <!-- Show this card only if status is 'completed' -->
            @if (strtolower($order->status) === 'completed')
            <div class="card mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h4">Order ID: #{{ $order->orderDetail->order_id_custom }}</h2>
                        <span class="badge bg-primary">{{ ucfirst($order->status) }}</span>
                    </div>
                    
                    <div>
                        <form action="{{ route('orders.refund', $order->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning"><i class="fa fa-undo"></i> Refund</button>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            <!-- Show this card only if status is 'ready for pickup' -->
            @if (strtolower($order->status) === 'ready for pickup')
            <div class="card mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h4">Order ID: #{{ $order->orderDetail->order_id_custom }}</h2>
                        <!-- Badge for Ready For Pickup status -->
                        <span class="badge bg-warning">{{ ucfirst($order->status) }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Show this card only if status is 'refunded' -->
            @if (strtolower($order->status) === 'refunded')
            <div class="card mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h4">Order ID: #{{ $order->orderDetail->order_id_custom }}</h2>
                        <!-- Badge for Ready For Pickup status -->
                        <span class="badge bg-warning">{{ ucfirst($order->status) }}</span>
                    </div>
                </div>
            </div>
            @endif

            <div class="row g-3">
                <!-- Order Items -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Order Items</h5>
                            <ul class="list-group list-group-flush">
                                @foreach($order->orderItems as $item)
                                <li class="list-group-item d-flex align-items-center">
                                    <span class="badge bg-dark me-3">{{ $item->quantity }}</span>
                                    <img src="{{ asset('assets/img/' . $item->product->product_image) }}" alt="{{ $item->product->product_name }}" class="rounded me-3" width="60">
                                    <div>
                                        <h6 class="mb-1">{{ $item->product->product_name }}</h6>
                                        <small class="text-muted">₱ {{ number_format($item->price, 2) }}</small>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Order Summary</h5>
                            <table class="table">
                                <tr>
                                    <td>Subtotal</td>
                                    <td class="text-end">₱ {{ number_format($order->orderItems->sum(fn($item) => $item->quantity * $item->price), 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Discount</td>
                                    <td class="text-end">₱ 0.00</td>
                                </tr>
                                <tr class="fw-bold">
                                    <td>Total</td>
                                    <td class="text-end">₱ {{ number_format($order->orderDetail->total_amount, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="card mt-3">
                        <div class="card-body">
                            <h5 class="card-title">Customer Information</h5>
                            <p class="mb-1">{{ $order->user->name }}</p>
                            <p class="text-muted"><i class="fa fa-phone me-2"></i>{{ $order->user->mobile }}</p>
                        </div>
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