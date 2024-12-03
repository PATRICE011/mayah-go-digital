@extends('home.layout')
@section('title','Mayah Store - Login')

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
            <a href="{{route('users.login')}}" class="header__top-action">Login</a>
            <span> / </span>
            <a href="{{route('users.register')}}" class="header__top-action"> Sign-up</a>
         </div>
      </div>
   </div>

   <nav class="nav container">
      <a href="{{route('home.index')}}" class="nav__logo">
         <i class="ri-restaurant-2-fill nav__logo-icon"></i> Mayah Store
      </a>

      <div class="nav__menu" id="nav-menu">
         <ul class="nav__list">
            <li class="nav__item">
               <a href="{{route('home.index')}}" class="nav__link">HOME</a>
            </li>

            <li class="nav__item">
               <a href="{{route('home.shop')}}" class="nav__link active-link">SHOP</a>
            </li>

            <li class="nav__item">
               <a href="myaccount.html" class="nav__link">MY ACCOUNT</a>
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
         <a href="{{route('home.wishlist')}}" class="header__action-btn">
            <i class='bx bx-heart' ></i>
            <span class="count">3</span>
         </a>

         <a href="{{route('home.cart')}}" class="header__action-btn">
            <i class='bx bx-cart-alt' ></i>
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
            <a href="{{route('home.index')}}" class="breadcrumb__link">
                Home
            </a>
        </li>

        <li>
            <span class="breadcrumb__link">
                >
            </span>
        </li>

        <li>
            <a href="{{route('users.register')}}" class="breadcrumb__link">
                Register
            </a>
        </li>
    </ul>
</section>

<!--==================== REGISTER ====================-->
<section class="login-register section--lg">
    <div class="login-register__container container grid">
        <div class="register">
            <h3 class="section__title">
                Register
            </h3>

            <form action="" class="form grid">
                <label for="name" class="login-register__label">Name</label>
                <input type="text" placeholder="Enter your Name" class="form__input">

                <label for="name" class="login-register__label">Phone Number</label>
                <input type="tel" placeholder="Enter your Phone Number" class="form__input">

                <label for="name" class="login-register__label">Password</label>
                <input type="password" placeholder="Enter you Password" class="form__input">

                <label for="name" class="login-register__label">Confirm Password</label>
                <input type="password" placeholder="Confirm Password" class="form__input">

                <div>
                    <p class="login__signup">
                        Already have an account? <a href="{{route('users.login')}}" class="login-register__link">Sign In</a>
                    </p>
                </div>

                <div class="form__btn">
                    <button class="btn">Register</button>
                </div>
            </form>
        </div>
    </div>
</section>

@include('home.footer')