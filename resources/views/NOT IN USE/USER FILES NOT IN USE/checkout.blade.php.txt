@extends('home.layout')
@section('title','checkout-page')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    You are paying
                    <strong>Mayah Store</strong>
                </div>
                <div class="card-body">
                    <p class="card-text">Payment for: This is the checkout description</p>

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Item Name</th>
                                <th scope="col">Qty</th>
                                <th scope="col">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($cartItems as $item)
                            <tr>
                                <td>{{ $item->product->product_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₱ {{ number_format($item->product->product_price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="total mb-3">
                        <h5>Total: <strong>₱ {{ number_format($cartItems->sum(function($item) { return $item->product->product_price * $item->quantity; }), 2) }}</strong></h5>
                    </div>

                    <!-- Single form submission -->
                    <form action="{{ route('goCheckout') }}" method="POST">
                        @csrf

                        <!-- Pass cart details as hidden inputs -->
                        @foreach($cartItems as $item)
                            <input type="hidden" name="cartItems[{{ $loop->index }}][product_id]" value="{{ $item->product->id }}">
                            <input type="hidden" name="cartItems[{{ $loop->index }}][quantity]" value="{{ $item->quantity }}">
                            <input type="hidden" name="cartItems[{{ $loop->index }}][price]" value="{{ $item->product->product_price }}">
                        @endforeach

                        <div class="mb-3">
                            <label for="paymentMethod" class="form-label">Payment Method</label>
                            <select class="form-select" id="paymentMethod" name="paymentMethod">
                                <option selected>Gcash</option>
                                <option value="PayMaya">PayMaya</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter your name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address (optional)</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="mobile" class="form-label">Mobile number (optional)</label>
                            <input type="text" class="form-control" id="mobile" name="mobile" value="{{ old('mobile') }}" placeholder="+63">
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" {{ old('terms') ? 'checked' : '' }} required>
                            <label class="form-check-label" for="terms">I have read and agree to the terms and conditions</label>
                        </div>
                        <button type="submit" class="btn btn-success">
                            Pay
                        </button>
                    </form>

                    <!-- Success and error messages -->
                    @if(session('success'))
                        <div class="alert alert-success mt-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger mt-3">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
