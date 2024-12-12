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
               <a href="{{url('/')}}" class="nav__link active-link">HOME</a>
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
            <input type="text" placeholder="Search Item" class="form__input">

            <button class="search__btn">
               <i class='bx bx-search search'></i>
            </button>
         </div>
      </div>

      <div class="header__user-actions">
         <a href="{{route('home.wishlist')}}" class="header__action-btn">
            <i class='bx bx-heart'></i>
            <span class="count">{{$wishlistCount}}</span>
         </a>

         <a href="{{route('home.cart')}}" class="header__action-btn">
            <i class='bx bx-cart-alt'></i>
            <span class="count">{{$cartCount}}</span>
         </a>
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
                About
            </span>
        </li>
    </ul>
</section>

<!--==================== ABOUT ====================-->
<section class="about section container">
   <h2 class="about__title">
      ABOUT US
   </h2>

   <div class="about__container">
      <p class="about__description">
      At <span>Mayah Store</span>, we understand that preparation is the key to success. <br><br>

      That's why we offer a comprehensive selection of products tailored to meet all your academic and personal needs.
      From basic school supplies like notebooks and pens to advanced tech gadgets that keep you ahead of the curve,
      our inventory is carefully curated to enhance your educational experience. <br><br>

      Dive into our extensive collection of high-quality items, including eco-friendly stationery,
      innovative study aids, and the latest electronic devices,
      all designed to support and inspire your journey towards academic excellence and personal growth.
      Whether you're gearing up for a new school year or tackling everyday challenges,
      Mayah Store is your trusted partner in achieving success and exceeding your goals.
      </p>
   </div>
</section>

@include('home.footer')