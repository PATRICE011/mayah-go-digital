@extends('home.layout')
@section('title','Mayah Store - About')

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
      <a href="{{url('/user')}}" class="nav__logo">
         <i class="ri-restaurant-2-fill nav__logo-icon"></i> Mayah Store
      </a>

      <div class="nav__menu" id="nav-menu">
         <div class="nav__menu-top">
            <a href="{{url('/user')}}" class="nav__menu-logo">
               <i class="ri-restaurant-2-fill nav__logo-icon"></i> Mayah Store
            </a>

            <div class="nav__close" id="nav-close"> 
               <i class="ri-close-line"></i>
            </div>
         </div>
         <ul class="nav__list">
            <li class="nav__item">
               <a href="{{url('/user')}}" class="nav__link active-link">HOME</a>
            </li>

            <li class="nav__item">
               <a href="{{url('/shop')}}" class="nav__link">SHOP</a>
            </li>

            @auth
            <li class="nav__item">
               <a href="{{url('/user/myaccount')}}" class="nav__link">MY ACCOUNT</a>
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
            <i class='bx bx-heart'></i><span class="count">{{$wishlistCount}}</span>
         </a>

         <a href="{{ url('/cart') }}" class="header__action-btn">
            <i class='bx bx-cart-alt'></i><span id="cart-count" class="count">{{ $cartCount }}</span>
         </a>

         <div class="header__action-btn nav__toggle" id="nav-toggle">
            <i class="ri-menu-line"></i>
         </div>
      </div>

   </nav>
</header>

@section('content')

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
                Privacy Policy
            </span>
        </li>
    </ul>
</section>


<!--==================== PRIVACY POLICY ====================-->
<section class="policy section container">
    <h2 class="policy__title">
        Privacy Policy for Mayah Store
    </h2>

    <div class="policy__container">
        <div class="policy__items">
            <div class="policy__item">
                <h2 class="policy__item-title">
                    1. Information We Collect
                </h2>

                <p class="policy__item-description">
                    We collect the following types of information to provide our services:
                </p>

                <ul class="policy__item-list">
                    <li>
                        <span>Personal Information: </span> Name, contact number, and email address for order confirmation and communication.
                    </li>

                    <li>
                        <span>Order Information: </span> Details of your purchased items and preferred pickup time.
                    </li>


                    <li>
                        <span>Browsing Information: </span> Cookies, IP address, and device information to enhance website functionality.
                    </li>

                </ul>
            </div>

            <div class="policy__item">
                <h2 class="policy__item-title">
                    2. How We Use Your Information
                </h2>

                <p class="policy__item-description">
                    We use the information collected to:
                </p>

                <ul class="policy__item-list">
                    <li>
                        Process and confirm your orders for pickup.
                    </li>

                    <li>
                        Communicate pickup instructions and updates.    
                    </li>

                    <li>
                        Improve our website and services.    
                    </li>

                    <li>
                        Send notifications or updates related to your orders (with your consent).
                    </li>
                </ul>
            </div>

            <div class="policy__item">
                <h2 class="policy__item-title">
                    3. Sharing Your Information
                </h2>

                <p class="policy__item-description">
                    We do not sell, rent, or share your personal information except when necessary to:
                </p>

                <ul class="policy__item-list">
                    <li>
                        Comply with legal obligations.
                    </li>

                    <li>
                        Protect the rights and safety of Mayah Store, its users, and others.
                    </li>
                </ul>
            </div>

            <div class="policy__item">
                <h2 class="policy__item-title">
                    4. Security Measures
                </h2>

                <p class="policy__item-description">
                    To protect your information, we implement:
                </p>

                <ul class="policy__item-list">
                    <li>
                        Secure storage of customer data.
                    </li>

                    <li>
                        Restricted access to personal information.
                    </li>

                    <li>
                        Regular monitoring of our systems for vulnerabilities.
                    </li>
                </ul>
            </div>

            <div class="policy__item">
                <h2 class="policy__item-title">
                    5. Cookies and Tracking Technologies
                </h2>

                <p class="policy__item-description">
                    We use cookies to:
                </p>

                <ul class="policy__item-list">
                    <li>
                        Analyze website traffic and improve performance.
                    </li>

                    <li>
                        Save your preferences for future visits.
                    </li>
                </ul>

                <p class="policy__item-description">
                    You can disable cookies via your browser settings, though this may impact website functionality.
                </p>
            </div>

            <div class="policy__item">
                <h2 class="policy__item-title">
                    6. Your Rights
                </h2>

                <p class="policy__item-description">
                    As a customer, you have the right to:
                </p>

                <ul class="policy__item-list">
                    <li>
                        Access, update, or delete your personal information.
                    </li>

                    <li>
                        Opt-out of receiving promotional messages.
                    </li>
                </ul>
            </div>

            <div class="policy__item">
                <h2 class="policy__item-title">
                    7. Pickup-Specific Terms
                </h2>

                <p class="policy__item-description">
                    Since Mayah Store is a <span>pickup-only service</span>, we collect only the information necessary to:
                </p>

                <ul class="policy__item-list">
                    <li>
                        Confirm and process your orders.
                    </li>

                    <li>
                        Provide you with timely updates regarding your pickup.
                    </li>
                </ul>

                <p class="policy__item-description">
                    Please ensure your contact details are accurate to avoid delays.
                </p>
            </div>
        </div>
    </div>
</section>

@include('home.footer')