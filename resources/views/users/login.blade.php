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
                    <a href="" class="nav__link active-link">SHOP</a>
                </li>

                @auth
                <li class="nav__item">
                    <a href="{{url('myaccount')}}" class="nav__link">MY ACCOUNT</a>
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
            <a href="" class="header__action-btn">
                <i class='bx bx-heart'></i>
                <span class="count">3</span>
            </a>

            <a href="" class="header__action-btn">
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
            <span class="breadcrumb__link">
                >
            </span>
        </li>

        <li>
            <a href="{{url('user/login')}}" class="breadcrumb__link">
                Login
            </a>
        </li>
    </ul>
</section>

<!--==================== LOGIN ====================-->
<section class="login-register section--lg">
    <div class="login-register__container container grid">
        <div class="login">
            <h3 class="section__title">
                Login
            </h3>

            <form action="{{url('/user/login')}}" method="POST" class="form grid">
                @csrf
                <!-- Mobile number input -->
                <label for="mobile" class="login-register__label">Phone Number</label>
                <input type="tel" id="mobile" name="mobile" value="{{ old('mobile') }}" placeholder="Enter your Phone Number" class="form__input @error('mobile') is-invalid @enderror">

                <!-- Password input -->
                <label for="password" class="login-register__label">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" class="form__input @error('password') is-invalid @enderror">

                <!-- Display validation errors for mobile and password -->
                @error('mobile')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror

                @error('password')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror

                <div>
                    <p class="login__signup">
                        Don't have an account? <a href="{{url('user/register')}}" class="login-register__link">Sign Up</a>
                    </p>
                </div>

                <div class="form__btn">
                    <button type="submit" class="btn">Login</button>
                </div>
            </form>

        </div>
    </div>
</section>

@include('home.footer')
@endsection