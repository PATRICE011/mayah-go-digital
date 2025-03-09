@extends('home.layout')
@section('title','Mayah Store - Details')

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
        <!-- Home Link -->
        <li>
            @auth
            <a href="{{ url('/user') }}" class="breadcrumb__link">Home</a>
            @else
            <a href="{{ url('/') }}" class="breadcrumb__link">Home</a>
            @endauth
        </li>

        <!-- Separator -->
        <li>
            <span class="breadcrumb__separator"> > </span>
        </li>

        <!-- Category Link -->
        <li>
            <span class="breadcrumb__link">
                {{ $product->category_name }}
            </span>
        </li>

        <!-- Separator -->
        <li>
            <span class="breadcrumb__separator"> > </span>
        </li>

        <!-- Product Name -->
        <li>
            <span class="breadcrumb__link">
                {{ $product->product_name }}
            </span>
        </li>
    </ul>
</section>


<!--==================== DETAILS ====================-->
<section id="details-page" class="details section--lg">
    <div class="details__container container grid">
        <div class="details__group">
            <img src="{{ asset('assets/img/' . $product->product_image) }}" alt="{{ $product->product_name }}" class="details__img">

            <div class="details__small-images grid">
                <img src="{{ asset('assets/img/' . $product->product_image) }}" alt="{{ $product->product_name }}" class="details__small-img">
                <!-- You can add more images if you have them -->
            </div>
        </div>

        <div class="details__group">
            <h3 class="details__title">{{ $product->product_name }}</h3>
            <p class="details__brand">Brand: <span>{{ $product->brand ?? 'Unknown' }}</span></p>

            <div class="details__price flex">
                <span class="new__price">₱ {{ number_format($product->product_price, 2) }}</span>
                @if($product->product_old_price)
                <span class="old__price">₱ {{ number_format($product->product_old_price, 2) }}</span>
                <span class="save__price">₱ {{ number_format($product->product_old_price - $product->product_price, 2) }} Off</span>
                @endif
            </div>

            <div class="short__description">
                {{ $product->product_description ?? 'No description available.' }}
            </div>

            <ul class="product__list">
                <li class="list__item flex">
                    <i class='bx bx-crown'></i> Premium Quality
                </li>

                <li class="list__item flex">
                    <i class='bx bx-refresh'></i> Return Policy
                </li>
            </ul>

            <div class="details__action">
                <!-- Quantity input with min = 1 and max = available stock -->

                <form action="{{ route('home.inserttocart') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <!-- <input type="number" name="quantity" value="1" class="quantity" min="1" max="{{ $product->product_stocks }}"> -->
                    <button type="button" class="btn btn--sm">Add to Cart</button>
                </form>


                <form id="wish-button-{{ $product->id }}" action="{{ route('addtowish', $product->id)}}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="#" class="details__action-btn" aria-label="Add To Wishlist" onclick="addToWishlist({{ $product->id }});">
                    <i class='bx bx-heart'></i>
                </a>
            </div>

            <ul class="details__meta">
                <li class="meta__list flex">
                    <span>Availability: </span> {{ $product->product_stocks }} Items in Stock
                </li>
            </ul>
        </div>
    </div>
</section>

@include('home.footer')


@endsection