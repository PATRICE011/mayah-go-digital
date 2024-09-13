<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
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

                    <form action="{{ route('goCheckout') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="paymentMethod" class="form-label">Payment Method</label>
                            <select class="form-select" id="paymentMethod" name="paymentMethod">
                                <option selected>Gcash</option>
                                <option value="1">PayMaya</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter your name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="mobile" class="form-label">Mobile number (optional)</label>
                            <input type="text" class="form-control" id="mobile" name="mobile" value="{{ old('mobile') }}" placeholder="+63">
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" {{ old('terms') ? 'checked' : '' }}>
                            <label class="form-check-label" for="terms">I have read and agree to the terms and conditions</label>
                        </div>
                        <button type="submit" class="btn btn-success">Pay</button>
                    </form>
                    
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
