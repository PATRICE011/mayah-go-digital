@extends('admins.layout')
@section('title', 'POS Order Details')
<!-- @section('content') -->
 
<main class="container-xl my-4 section">
    <div class="row justify-content-center">
        <div class="col-12">
            <h1 class="display-6">POS Order Details</h1>

            <div class="row g-3">
                <!-- Order Items -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Order Items</h5>
                            <ul class="list-group list-group-flush">
                                {{-- @foreach($order->orderItems as $item) --}}
                                <li class="list-group-item d-flex align-items-center">
                                    <span class="badge bg-dark me-3">1</span>
                                    <img src="" alt="" class="rounded me-3" width="60">
                                    <div>
                                        <h6 class="mb-1">Bread Stix</h6>
                                        <small class="text-muted">₱ 5</small>
                                    </div>
                                </li>
                                {{-- @endforeach --}}
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
                                    <td class="text-end">₱ 5</td>
                                </tr>
                                <tr>
                                    <td>Discount</td>
                                    <td class="text-end">₱ 0.00</td>
                                </tr>
                                <tr class="fw-bold">
                                    <td>Total</td>
                                    <td class="text-end">₱ 5</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="card mt-3">
                        <div class="card-body">
                            <h5 class="card-title">Customer Information</h5>
                            <p class="mb-1">Anthony</p>
                            <p class="text-muted"><i class="fa fa-phone me-2"></i>0912 345 6789</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
