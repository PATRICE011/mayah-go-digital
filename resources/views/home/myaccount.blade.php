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
            <!-- For guest (non-authenticated users) -->
            <a href="{{url('user/login')}}" class="header__top-action">Login</a>
            <span> / </span>
            <a href="{{url('user/register')}}" class="header__top-action"> Sign-up</a>
            @else
            <!-- For authenticated users -->
            <!-- For authenticated users -->
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
            <span class="count">3</span> <!-- This should be dynamically populated -->
         </a>

         <a href="{{ url('cart') }}" class="header__action-btn">
            <i class='bx bx-cart-alt'></i>
            <span class="count">3</span> <!-- This should be dynamically populated -->
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
    </ul>
</section>

<!--==================== MY ACCOUNT ====================-->
<section class="accounts section--lg">
    <div class="accounts__container container grid">
        <div class="account__tabs">
            <p class="account__tab active-tab" data-target="#dashboard">
                <i class='bx bx-box' ></i> Dashboard
            </p>

            <p class="account__tab" data-target="#orders">
                <i class='bx bx-cart-download' ></i> Orders
            </p>

            <p class="account__tab" data-target="#update-profile">
                <i class='bx bxs-hand-up' ></i> Update Profile
            </p>

            <p class="account__tab" data-target="#change-password">
                <i class='bx bx-cog' ></i> Change Password
            </p>

            <p class="account__tab">
                <i class='bx bx-exit' ></i> Logout
            </p>
        </div>
        
        <div class="tabs__content">
            @if ($activeSection == 'dashboard')
            <div class="tab__content active-tab" content id="dashboard">
                <h3 class="tab__header">Hello "Name Here"</h3>

                <div class="tab__body">
                    <div class="stat__container">
                    <div class="stat-box">
                        <div class="icon icon-total-orders">
                            <i class="ri-building-fill"></i>
                        </div>

                            <h4 class="total-orders__quantity">3</h4>
                            <p class="total-orders__title">Total Orders</p>
                        </div>

                        <div class="stat-box">
                            <div class="icon icon-total-completed">
                                <i class="ri-archive-fill"></i>
                            </div>

                            <h4 class="total-completed__quantity">2</h4>
                            <p class="total-completed__title">Total Completed</p>
                        </div>

                        <div class="stat-box">
                            <div class="icon icon-total-returned">
                                <i class="ri-corner-up-left-fill"></i>
                            </div>

                            <h4 class="total-returned__quantity">1</h4>
                            <p class="total-returned__title">Total Returned</p>
                        </div>

                        <div class="stat-box">
                            <div class="icon icon-wallet-balance">
                                <i class="ri-wallet-fill"></i>
                            </div>

                            <h4 class="wallet-balance__quantity">₱0.00</h4>
                            <p class="wallet-balance__title">Wallet Balance</p>
                        </div>
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

                        <tr>
                            <td>#1</td>
                            <td>December 3, 2024</td>
                            <td>Ready for Pickup</td>
                            <td>₱ 7.00</td>
                            <td>
                                <a href="#" class="view__order">View</a>
                            </td>
                        </tr>

                        <tr>
                            <td>#1</td>
                            <td>December 3, 2024</td>
                            <td>Ready for Pickup</td>
                            <td>₱ 7.00</td>
                            <td>
                                <a href="#" class="view__order">View</a>
                            </td>
                        </tr>

                        <tr>
                            <td>#1</td>
                            <td>December 3, 2024</td>
                            <td>Ready for Pickup</td>
                            <td>₱ 7.00</td>
                            <td>
                                <a href="#" class="view__order">View</a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            @endif

            <div class="tab__content" content id="orders">
                <h3 class="tab__header">Your Orders</h3>

                <div class="tab__body">
                    <table class="placed__order-table">
                        <tr>
                            <th>Orders</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>

                        <tr>
                            <td>#1</td>
                            <td>December 3, 2024</td>
                            <td>Ready for Pickup</td>
                            <td>₱ 7.00</td>
                            <td>
                                <a href="{{url('/orderdetails')}}" class="view__order">View</a>
                            </td>
                        </tr>

                        <tr>
                            <td>#1</td>
                            <td>December 3, 2024</td>
                            <td>Ready for Pickup</td>
                            <td>₱ 7.00</td>
                            <td>
                                <a href="#" class="view__order">View</a>
                            </td>
                        </tr>

                        <tr>
                            <td>#1</td>
                            <td>December 3, 2024</td>
                            <td>Ready for Pickup</td>
                            <td>₱ 7.00</td>
                            <td>
                                <a href="#" class="view__order">View</a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="tab__content" id="update-profile">
                <h3 class="tab__header">Update Profile</h3>
                <div class="tab__body">
                    <!-- Profile Update Form -->
                    <form id="profile-update-form" action="{{ route('user.update-profile') }}" method="POST" class="form grid">
                        @csrf
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" placeholder="Name" class="form__input @error('name') is-invalid @enderror">
                        <input type="tel" name="mobile" value="{{ old('mobile', auth()->user()->mobile) }}" placeholder="Phone Number" class="form__input @error('mobile') is-invalid @enderror">
                        
                        <div>
                            <input type="tel" name="mobile" placeholder="Enter OTP" class="form__input">
                            <button class="btn btn--md">Get OTP</button>
                        </div>

                        <div class="form__btn">
                            <a href="{{url('')}}">
                                <button class="btn btn--md">
                                    Update Profile
                                </button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

                <!-- OTP Modal -->
                <!-- <div id="otp-modal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h3>Enter OTP</h3>
                        <form id="otp-form" action="{{ route('user.verify-otp') }}" method="POST">
                            @csrf
                            <input type="number" name="otp" placeholder="Enter OTP" class="form__input @error('otp') is-invalid @enderror">
                            <div class="form__btn">
                                <button type="submit" class="btn btn--md">Submit OTP</button>
                            </div>
                        </form>
                    </div>
                </div> -->
            </div>

            <div class="tab__content" content id="change-password">
                <h3 class="tab__header">Change Password</h3>

                <div class="tab__body">
                    <form action="" class="form grid">
                        <input type="password" placeholder="Old Password" class="form__input">
                        <input type="password" placeholder="New Password" class="form__input">
                        <input type="password" placeholder="Confirm Password" class="form__input">

                        <div>
                            <input type="tel" name="mobile" placeholder="Enter OTP" class="form__input">
                            <button class="btn btn--md">Get OTP</button>
                        </div>

                        <div class="form__btn">
                            <button class="btn btn--md">
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@include('home.footer')