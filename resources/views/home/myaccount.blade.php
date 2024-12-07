@extends('home.layout')

@section('title','Mayah Store - My Account')

@section('styles')
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        #otp-modal {
            display: none;
        }
    </style>
@endsection

<header class="header" id="header">
    <div class="header__top">
        <div class="header__container container">
            <div class="header__contact">
                <span><i class="ri-map-pin-fill"></i> Valenzuela, Philippines</span>
            </div>

            <p class="header__alert-news">
                Super Value Deals - Save More!
            </p>

            <div>
                @guest
                    <a href="{{url('user/login')}}" class="header__top-action">Login</a>
                    <span> / </span>
                    <a href="{{url('user/register')}}" class="header__top-action">Sign-up</a>
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

<<<<<<< HEAD
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
=======
        <div class="nav__menu" id="nav-menu">
            <ul class="nav__list">
                <li class="nav__item"><a href="{{url('/')}}" class="nav__link">HOME</a></li>
                <li class="nav__item"><a href="{{url('/shop')}}" class="nav__link">SHOP</a></li>
                @auth
                    <li class="nav__item"><a href="{{url('myaccount')}}" class="nav__link active-link">MY ACCOUNT</a></li>
                @endauth
            </ul>
        </div>
    </nav>
>>>>>>> 009ea73adb05c0ef2e471a76379c1586a046a480
</header>

<section class="breadcrumb">
    <ul class="breadcrumb__list flex container">
        <li><a href="{{url('/')}}" class="breadcrumb__link">Home</a></li>
        <li><span class="breadcrumb__link"> > </span></li>
        <li><span class="breadcrumb__link">Account</span></li>
    </ul>
</section>

<section class="accounts section--lg">
    <div class="accounts__container container grid">
        <div class="account__tabs">
            <p class="account__tab active-tab" data-target="#dashboard"><i class='bx bx-box'></i> Dashboard</p>
            <p class="account__tab" data-target="#orders"><i class='bx bx-cart-download'></i> Orders</p>
            <p class="account__tab" data-target="#update-profile"><i class='bx bxs-hand-up'></i> Update Profile</p>
            <p class="account__tab" data-target="#change-password"><i class='bx bx-cog'></i> Change Password</p>
            <p class="account__tab"><i class='bx bx-exit'></i> Logout</p>
        </div>

        <div class="tabs__content">
            <div class="tab__content active-tab" id="update-profile">
                <h3 class="tab__header">Update Profile</h3>
                <div class="tab__body">
                    <!-- Profile Update Form -->
                    <form id="profile-update-form" action="{{ route('user.update-profile') }}" method="POST" class="form grid">
                        @csrf
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" placeholder="Name" class="form__input @error('name') is-invalid @enderror">
                        <input type="tel" name="mobile" value="{{ old('mobile', auth()->user()->mobile) }}" placeholder="Phone Number" class="form__input @error('mobile') is-invalid @enderror">
                        <div class="form__btn">
                            <button type="submit" class="btn btn--md">Update Profile</button>
                        </div>
                    </form>
                </div>

                <!-- OTP Modal -->
                <div id="otp-modal" class="modal">
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
                </div>
            </div>
        </div>
    </div>
</section>

@section('scripts')
@endsection

@include('home.footer')
