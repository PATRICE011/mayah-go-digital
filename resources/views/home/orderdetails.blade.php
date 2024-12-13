@extends('home.layout')
@section('title','Mayah Store - My Account')

<header class="header" id="header">
    <div class="header__top">
        <div class="header__container container">
            <div class="header__contact">
                <span>
                    <i class="ri-map-pin-fill"></i> Valenzuela, Philippines
                </span>
            </div>

            <p class="header__alert-news">
                Super Value Deals - Save More!
            </p>

            <div>
                @guest
                <a href="{{url('user/login')}}" class="header__top-action">Login</a>
                <span> / </span>
                <a href="{{url('user/register')}}" class="header__top-action"> Sign-up</a>
                @else

                @auth
                <form action="{{ url('/logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="header__top-action" style="border: none; background: none; cursor: pointer;">Logout</button>
                </form>
                @endauth

                <span> / </span>
                <span class="header__top-action">Welcome, {{ Auth::user()->name }}</span>
                @endguest
            </div>
        </div>
    </div>

    <nav class="nav container">
        <a href="{{url('/')}}" class="nav__logo">
            <i class="ri-restaurant-2-fill nav__logo-icon"></i> Mayah Store
        </a>

        <div class="nav__menu" id="nav-menu">
            <ul class="nav__list">
                <li class="nav__item">
                    <a href="{{url('/')}}" class="nav__link">HOME</a>
                </li>

                <li class="nav__item">
                    <a href="{{url('/shop')}}" class="nav__link">SHOP</a>
                </li>

                @auth
                <li class="nav__item">
                    <a href="{{url('myaccount')}}" class="nav__link active-link">MY ACCOUNT</a>
                </li>
                @endauth
            </ul>

            <div class="header__search">
                <input type="text" placeholder="Search Item" class="form__input">
                <button class="search__btn">
                    <i class='bx bx-search search'></i>
                </button>
            </div>
        </div>

        <div class="header__user-actions">
            <a href="{{ url('wishlist') }}" class="header__action-btn">
                <i class='bx bx-heart'></i>
                <span class="count">0</span>
            </a>

            <a href="{{ url('cart') }}" class="header__action-btn">
                <i class='bx bx-cart-alt'></i>
                <span class="count">0</span>
            </a>
        </div>
    </nav>
</header>

<!--==================== BREADCRUMB ====================-->
<section class="breadcrumb">
    <ul class="breadcrumb__list flex container">
        <li>
            <a href="{{url('/')}}" class="breadcrumb__link">
                Home
            </a>
        </li>

        <li>
            <span class="breadcrumb__link">
                >
            </span>
        </li>

        <li>
            <span class="breadcrumb__link">
                Account
            </span>
        </li>

        <li>
            <span class="breadcrumb__link">
                >
            </span>
        </li>

        <li>
            <span class="breadcrumb__link">
                View
            </span>
        </li>
    </ul>
</section>

<!--==================== MY ACCOUNT ====================-->
<section class="accounts section--lg">
    <div class="accounts__container container grid">
        <div class="account__tabs">
            <p class="account__tab">
                <i class='bx bx-box'></i>
                <a href="{{ route('myaccount.dashboard') }}" class="account__link"> Dashboard</a>
            </p>

            <p class="account__tab active-tab" data-target="#orders">
                <i class='bx bx-cart-download'></i> Orders
            </p>

            <p class="account__tab" data-target="#update-profile">
                <i class='bx bxs-hand-up'></i> Update Profile
            </p>

            <p class="account__tab" data-target="#change-password">
                <i class='bx bx-cog'></i> Change Password
            </p>

            <p class="account__tab">
                <i class='bx bx-exit'></i> Logout
            </p>
        </div>

        <div class="tabs__content">
            <div class="tab__content" content id="dashboard">
            </div>

            <div class="tab__content active-tab" content id="orders">
                <div class="order-container">
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
                            {{ $order->status == 'pending' ? 'active' : '' }}">
                            <span>Pending</span>
                        </div>

                        <!-- Step: Confirmed -->
                        <div class="order-progress-step 
                            {{ $order->status == 'confirmed' ? 'active' : '' }}">
                            {{ in_array($order->status, ['confirmed', 'ready-for-pickup', 'completed']) ? 'completed' : '' }} 
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
                            <button class="btn btn--md">
                                Invoice
                            </button>
                        </a>
                    </div>
                </div>
            </div>

            <div class="tab__content" content id="update-profile">
            </div>

            <div class="tab__content" content id="change-password">
            </div>
        </div>
    </div>
</section>

@include('home.footer')