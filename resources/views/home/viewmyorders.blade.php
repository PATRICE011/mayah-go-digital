<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--=============== FAVICON ===============-->
    <link rel="shortcut icon" href="{{ asset('assets/img/MAYAH-STORE-LOGO.jpg') }}" type="image/x-icon">

    <!--=============== REMIXICONS ===============-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">

    <!--=============== BOXICONS ===============-->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/overview.css') }}">

    <!-- ====== Toastr ========-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <title>Order Details</title>
</head>

<body>
    @include('users.header')

    <main class="main">
        <section class="myorders" id="myorders">
            <div class="container grid blockings">
                <div class="sidebar">
                    <h2>Will Smith</h2>
                    <p>+880125333344</p>
                    <ul>
                        <li><a href="#" class="sidebar-link" onclick="showSection('overview', event)">Overview</a></li>
                        <li><a href="#" class="sidebar-link" onclick="showSection('order-history', event)">Order History</a></li>
                        <li><a href="#" class="sidebar-link" onclick="showSection('return-orders', event)">Return Orders</a></li>
                        <li><a href="#" class="sidebar-link" onclick="showSection('account-info', event)">Account Info</a></li>
                        <li><a href="#" class="sidebar-link" onclick="showSection('change-password', event)">Change Password</a></li>
                        <li><a href="#" class="sidebar-link" onclick="showSection('address', event)">Address</a></li>
                    </ul>
                </div>

                <div class="content">
                    <div class="order-details-container">
                        <h3 class="order-header">
                            <a href="#"><i class="ri-arrow-left-line"></i></a>
                            Order Details
                        </h3>

                        @if ($latestOrder)
                        <h1 class="order-thank-you">Thank You</h1>
                        <p class="order-status-text">Your Order status is as follows</p>
                        <p class="order-status-id">Order ID: <strong>#{{ $latestOrder->id }}</strong></p>

                        <div class="progress-bar">
                            <!-- Background line for the entire progress bar -->
                            <div class="progress-line"></div>

                            <!-- Progress line showing completed portion based on the status -->
                            <div class="progress-line completed" style="width:
                                @if($latestOrder->status === 'paid') 12%;
                                @elseif($latestOrder->status === 'confirmed') 36%;
                                @elseif($latestOrder->status === 'ready-for-pickup') 64%;
                                @elseif($latestOrder->status === 'completed') 100%;
                                @endif">
                            </div>

                            <!-- Progress Steps -->
                            <div class="progress-step {{ $latestOrder->status === 'paid' || $latestOrder->status !== null ? 'completed' : '' }}">
                                <div class="progress-icon">{{ $latestOrder->status === 'paid' ? '✓' : '•' }}</div>
                                <div class="progress-text">Order Pending</div>
                            </div>

                            <div class="progress-step {{ in_array($latestOrder->status, ['confirmed', 'ready-for-pickup', 'completed']) ? 'completed' : '' }}">
                                <div class="progress-icon">{{ $latestOrder->status === 'confirmed' ? '✓' : '•' }}</div>
                                <div class="progress-text">Order Confirmed</div>
                            </div>

                            <div class="progress-step {{ in_array($latestOrder->status, ['ready-for-pickup', 'completed']) ? 'completed' : '' }}">
                                <div class="progress-icon">{{ $latestOrder->status === 'ready-for-pickup' ? '✓' : '•' }}</div>
                                <div class="progress-text">Ready for Pickup</div>
                            </div>

                            <div class="progress-step {{ $latestOrder->status === 'completed' ? 'completed' : '' }}">
                                <div class="progress-icon">{{ $latestOrder->status === 'completed' ? '✓' : '•' }}</div>
                                <div class="progress-text">Completed</div>
                            </div>
                        </div>


                        <!-- Order Information -->
                        <div class="order-info">
                            <p><strong>Order Date:</strong> {{ $latestOrder->created_at->format('d.m.Y H:i') }}</p>
                            <p><strong>Order Status:</strong> <span class="badge {{ strtolower($latestOrder->status) }}">{{ ucfirst(str_replace('_', ' ', $latestOrder->status)) }}</span></p>
                            <p><strong>Payment Method:</strong> {{ $latestOrder->orderDetail->payment_method ?? 'N/A' }}</p>
                        </div>

                        <!-- Order Summary -->
                        <h3 class="order-summary-header">Order Summary</h3>
                        @foreach ($latestOrder->orderItems as $item)
                        <div class="product-item">
                            <div class="product-image">
                                <img src="{{ asset('assets/img/' . $item->product->product_image) }}" alt="{{ $item->product->product_name }}">
                            </div>
                            <div class="product-details">
                                <div class="product-name">{{ $item->product->product_name }}</div>
                                <div class="product-info">
                                    {{ $item->product->category->category_name ?? 'Category not specified' }}
                                </div>
                                <div class="product-price">₱{{ number_format($item->price, 2) }}</div>
                                <div class="product-info">Quantity: {{ $item->quantity }}</div>
                            </div>
                        </div>
                        @endforeach

                        <div class="summary-line">
                            <span>Subtotal</span>
                            <span>₱{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="summary-line">
                            <span>Discount</span>
                            <span>₱0.00</span>
                        </div>
                        <div class="summary-line total">
                            <span>Total</span>
                            <span>₱{{ number_format($total, 2) }}</span>
                        </div>
                        @else
                        <p>No recent orders found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!--=============== JavaScript for Switching Sections ===============-->
    <script>
        function showSection(sectionId) {
            // Hide all content sections
            const sections = document.querySelectorAll('.content-section');
            sections.forEach(section => section.classList.remove('active'));

            // Show the selected content section
            document.getElementById(sectionId).classList.add('active');

            // Remove active class from all sidebar links
            const links = document.querySelectorAll('.sidebar-link');
            links.forEach(link => link.classList.remove('active'));

            // Add active class to the clicked link
            event.currentTarget.classList.add('active');
        }
    </script>

    <!--=============== SCROLL REVEAL ANIMATION ===============-->
    <script src="{{ asset('assets/js/scrollreveal.min.js') }}"></script>

    <!--=============== MIXITUP FILTER ===============-->
    <script src="{{ asset('assets/js/mixitup.min.js') }}"></script>

    <!--=============== MAIN JS ===============-->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Latest Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>