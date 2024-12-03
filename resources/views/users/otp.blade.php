@extends('home.layout')
@section('title','Mayah Store - OTP Verification')

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
            <a href="{{url('user/login')}}" class="header__top-action">Login</a>
            <span> / </span>
            <a href="{{url('user/register')}}" class="header__top-action"> Sign-up</a>
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
               <a href="{{url('shop')}}" class="nav__link">SHOP</a>
            </li>

            <li class="nav__item">
               <a href="{{url('myaccount')}}" class="nav__link">MY ACCOUNT</a>
            </li>
         </ul>

         <div class="header__search">
            <input type="text" placeholder="Search Item" class="form__input">

            <button class="search__btn">
               <i class='bx bx-search search'></i>
            </button>
         </div>
      </div>

      <div class="header__user-actions">
         <a href="{{url('wishlist')}}" class="header__action-btn">
            <i class='bx bx-heart'></i>
            <span class="count">3</span>
         </a>

         <a href="{{url('cart')}}" class="header__action-btn">
            <i class='bx bx-cart-alt'></i>
            <span class="count">3</span>
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
         <span class="breadcrumb__link"> > </span>
      </li>

      <li>
         <span class="breadcrumb__link">OTP</span>
      </li>
   </ul>
</section>

<!--==================== OTP ====================-->
<section class="login-register section--lg">
   <div class="login-register__container container grid">
      <div class="register">
         <h3 class="section__title">OTP</h3>

         <form action="{{ url('user/otp') }}" method="POST" class="form grid">
            @csrf
            <label for="otp" class="login-register__label">One-Time Password</label>
            <input type="text" id="otp" name="otp" placeholder="Enter OTP" class="form__input" required>

            <div>
               <p class="login__signup">
                  Didn't receive a code? <a href="{{ url('user/resend-otp') }}">Resend Code</a>
               </p>
            </div>

            <div class="form__btn">
               <button type="submit" class="btn">Submit</button>
            </div>
         </form>
      </div>
   </div>
</section>

@include('home.footer')

<script>
    toastr.options = {
      "closeButton": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "timeOut": "5000",
    };

    // Display success message if available
    @if (session('message'))
      toastr.success("{{ session('message') }}");
    @endif

    // Display error message if available
    @if (session('error'))
      toastr.error("{{ session('error') }}");
    @endif
  </script>

@endsection
