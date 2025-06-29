@extends('home.layout')
@section('title','Mayah Store - My Account')

@section('content')
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
                <!-- For guest (non-authenticated users) -->
                <a href="{{url('user/login')}}" class="header__top-action">Login</a>
                <span> / </span>
                <a href="{{url('user/register')}}" class="header__top-action"> Sign-up</a>
                @else

                <!-- For authenticated users -->
                @auth
                <form action="{{ route('users.logout') }}" method="POST" style="display: inline;">
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
        @auth
        <a href="{{url('/user')}}" class="nav__logo">
            <i class="ri-restaurant-2-fill nav__logo-icon"></i> Mayah Store
        </a>
        @else
        <a href="{{url('/')}}" class="nav__logo">
            <i class="ri-restaurant-2-fill nav__logo-icon"></i> Mayah Store
        </a>
        @endif

        <div class="nav__menu" id="nav-menu">
            <div class="nav__menu-top">
                <div>
                    @guest
                    <!-- For guest (non-authenticated users) -->
                    <a href="{{url('user/login')}}" class="header__top-action">Login</a>
                    <span> / </span>
                    <a href="{{url('user/register')}}" class="header__top-action"> Sign-up</a>
                    @else

                    <!-- For authenticated users -->
                    @auth
                    <form action="{{ route('users.logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="header__top-action" style="border: none; background: none; cursor: pointer;">Logout</button>
                    </form>
                    @endauth

                    <span> / </span>
                    <span class="header__top-action">Welcome, {{ Auth::user()->name }}</span>
                    @endguest
                </div>

                <div class="nav__close" id="nav-close">
                    <i class="ri-close-line"></i>
                </div>
            </div>
            
            <ul class="nav__list">
                <li class="nav__item">
                    <a href="{{url('/user')}}" class="nav__link">HOME</a>
                </li>

                <li class="nav__item">
                    <a href="{{url('/shop')}}" class="nav__link">SHOP</a>
                </li>

                @auth
                <li class="nav__item">
                    <a href="{{url('/user/myaccount')}}" class="nav__link active-link">MY ACCOUNT</a>
                </li>
                @endauth
            </ul>

            <div class="header__search">
                <form action="{{route('home.shop')}}" method="GET">
                    @csrf
                    <input
                        type="text"
                        name="search"
                        placeholder="Search Item"
                        class="form__input"
                        id="searchInput">
                    <button class="search__btn" id="searchButton">
                        <i class='bx bx-search search'></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="header__user-actions">
            <a href="{{url('/wishlist')}}" class="header__action-btn">
                <i class='bx bx-heart'></i><span class="count">0</span>
            </a>

            <a href="{{ url('/cart') }}" class="header__action-btn">
                <i class='bx bx-cart-alt'></i><span id="cart-count" class="count">0</span>
            </a>

            <div class="header__action-btn nav__toggle" id="nav-toggle">
                <i class="ri-menu-line"></i>
            </div>
        </div>
    </nav>
</header>

<!--==================== BREADCRUMB ====================-->
<section class="breadcrumb">
    <ul class="breadcrumb__list flex container">
        <li>
            @auth
            <a href="{{url('/user')}}" class="breadcrumb__link">
                Home
            </a>
            @else
            <a href="{{url('/')}}" class="breadcrumb__link">
                Home
            </a>
            @endauth
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
    </ul>
</section>

<!--==================== MY ACCOUNT ====================-->
<section class="accounts section--lg">
    <div class="accounts__container container grid">
        <div class="account__tabs">
            <p class="account__tab {{ session('active_tab') == 'dashboard' ? 'active-tab' : '' }}" data-target="#dashboard">
                <i class='bx bx-box'></i> Dashboard
            </p>
            <p class="account__tab {{ session('active_tab') == 'orders' ? 'active-tab' : '' }}" data-target="#orders">
                <i class='bx bx-cart-download'></i> Orders
            </p>
            <p class="account__tab {{session('active_tab')  == 'update-profile' ? 'active-tab' : '' }}" data-target="#update-profile">
                <i class='bx bxs-hand-up'></i> Update Profile
            </p>
            <p class="account__tab {{ session('active_tab')  == 'change-password' ? 'active-tab' : '' }}" data-target="#change-password">
                <i class='bx bx-cog'></i> Change Password
            </p>

            <!-- Logout Tab (with dynamic handling) -->
            @auth
            <form id="logout-form" action="{{ route('users.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <p class="account__tab" onclick="document.getElementById('logout-form').submit();">
                <i class='bx bx-exit'></i> Logout
            </p>
            @endauth
        </div>

        <div class="tabs__content">
            <div class="tabs__content">
                <!-- Dashboard Tab Content -->
                <div class="tab__content {{ session('active_tab', 'dashboard') == 'dashboard' ? 'active-tab' : '' }}" id="dashboard">
                    <h3 class="tab__header">Hello {{ Auth::user()->name }}</h3>

                    <div class="tab__body">
                        <div class="stat__container">
                            <div class="stat-box">
                                <div class="icon icon-total-orders">
                                    <i class="ri-building-fill"></i>
                                </div>
                                <h4 class="total-orders__quantity">
                                    {{ $orders->where('status', '!=', 'pending')->count() }}
                                </h4>
                                <p class="total-orders__title">Total Orders</p>
                            </div>

                            <div class="stat-box">
                                <div class="icon icon-total-completed">
                                    <i class="ri-archive-fill"></i>
                                </div>
                                <h4 class="total-completed__quantity">
                                    {{ $orders->where('status', 'completed')->count() }}
                                </h4>
                                <p class="total-completed__title">Total Completed</p>
                            </div>

                            <!-- <div class="stat-box">
                                <div class="icon icon-total-returned">
                                    <i class="ri-corner-up-left-fill"></i>
                                </div>
                                <h4 class="total-returned__quantity">
                                    {{ $orders->where('status', 'returned')->count() }}
                                </h4>
                                <p class="total-returned__title">Total Returned</p>
                            </div>

                            <div class="stat-box">
                                <div class="icon icon-wallet-balance">
                                    <i class="ri-wallet-fill"></i>
                                </div>
                                <h4 class="wallet-balance__quantity">₱{{ number_format($user->wallet_balance, 2) }}</h4>
                                <p class="wallet-balance__title">Wallet Balance</p>
                            </div> -->
                        </div>

                        <h3 class="tab__header-title">Recent Orders</h3>

                        <table class="placed__order-table">
                            <tr>
                                <th>Orders</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>

                            @forelse ($orders->filter(fn($order) => $order->status !== 'pending') as $order)
                            <tr>
                                <td>#{{ $order->order_id_custom }}</td>
                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('F j, Y') }}</td>
                                <td>{{ ucfirst($order->status) }}</td>
                                <td>₱{{ number_format($order->subtotal, 2) }}</td>
                                <td>
                                    <a href="javascript:void(0);"
                                        class="view__order"
                                        data-order-id="{{ $order->order_id }}"
                                        onclick="showDashboardOrderDetails(event, '{{ $order->order_id }}')">
                                        View
                                    </a>
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="no-orders">No orders found</td>
                            </tr>
                            @endforelse
                        </table>
                    </div>
                </div>

                <!-- Add other tabs here with their respective content -->
            </div>


            <div class="tab__content {{ session('active_tab')  == 'orders' ? 'active-tab' : '' }}" content id="orders">
                <h3 class="tab__header">Your Orders</h3>
                <div class="tab__body" id="orders-list-container">
                    <table class="placed__order-table">
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                        @forelse ($orders as $order)
                        <tr>
                            <td>{{ $order->order_id_custom }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('F j, Y') }}</td>
                            <td> @if ($order->status == 'paid')
                                Pending
                                @else
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                @endif
                            </td>
                            <td>₱ {{ number_format($order->subtotal, 2) }}</td>
                            <td>

                                @if ($order->status == 'pending')
                                <a href="{{ route('cart.pay', ['orderId' => $order->order_id]) }}" class="view__order">Pay</a>
                                @else
                                <a href="javascript:void(0);" class="view__order" data-order-id="{{ $order->order_id }}" onclick="showOrderDetails(event, '{{ $order->order_id }}')">View</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center;">No orders found.</td>
                        </tr>
                        @endforelse
                    </table>
                </div>
            </div>

            <div class="tab__content {{ session('active_tab') == 'update-profile' ? 'active-tab' : '' }}" content id="update-profile">
                <h3 class="tab__header">Update Profile</h3>
                <div class="tab__body">
                    <form id="profile-update-form" action="{{ route('user.update-profile') }}" method="POST" class="form grid">
                        @csrf
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" placeholder="Name" class="form__input @error('name') is-invalid @enderror">
                        @error('name')
                        <div class="error">{{ $message }}</div>
                        @enderror

                        <input type="tel" name="mobile" value="{{ old('mobile', auth()->user()->mobile) }}" placeholder="Phone Number" class="form__input @error('mobile') is-invalid @enderror">
                        @error('mobile')
                        <div class="error">{{ $message }}</div>
                        @enderror

                        <div>
                            <input type="tel" name="otp" placeholder="Enter OTP" class="form__input @error('otp') is-invalid @enderror">
                            <button type="button"
                                id="get-otp-button-update-profile"
                                class="btn btn--md"
                                data-url="{{ route('sendCode') }}"
                                data-action="update-profile"
                                data-csrf="{{ csrf_token() }}">
                                Get OTP
                            </button>

                            @error('otp')
                            <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form__btn">
                            <button type="submit" class="btn btn--md">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>



            <div class="tab__content {{ session('active_tab') == 'change-password' ? 'active-tab' : '' }}" content id="change-password">
                <h3 class="tab__header">Change Password</h3>
                <div class="tab__body">
                    <form action="{{ route('changePassword') }}" method="POST" class="form grid">
                        @csrf
                        <!-- Old Password -->
                        <input
                            type="password"
                            name="old_password"
                            value="{{ old('old_password') }}"
                            placeholder="Old Password"
                            class="form__input">
                        @error('old_password')
                        <div class="error">{{ $message }}</div>
                        @enderror
                        <!-- New Password -->
                        <input
                            type="password"
                            name="new_password"
                            value="{{ old('new_password') }}"
                            placeholder="New Password"
                            class="form__input">
                        @error('new_password')
                        <div class="error">{{ $message }}</div>
                        @enderror

                        <!-- Confirm New Password -->
                        <input
                            type="password"
                            name="new_password_confirmation"
                            value="{{ old('new_password_confirmation') }}"
                            placeholder="Confirm Password"
                            class="form__input">

                        <!-- OTP -->
                        <div>
                            <input
                                type="tel"
                                name="otp"
                                value="{{ old('otp') }}"
                                placeholder="Enter OTP"
                                class="form__input">
                            <button
                                type="button"
                                id="get-otp-button-change-password"
                                class="btn btn--md"
                                data-url="{{ route('sendCode') }}"
                                data-action="change-password"
                                data-csrf="{{ csrf_token() }}">
                                Get OTP
                            </button>
                            @error('otp')
                            <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="form__btn">
                            <button type="submit" class="btn btn--md">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>



        </div>
    </div>
</section>

@include('home.footer')
@endsection
<script>
    window.appConfig = {
        activeTab: @json(session('active_tab', 'dashboard'))
    };
</script>