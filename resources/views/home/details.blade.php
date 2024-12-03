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
               <a href="{{url('/myaccount')}}" class="nav__link">MY ACCOUNT</a>
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

         <a href="{{ url('user/cart') }}" class="header__action-btn">
            <i class='bx bx-cart-alt'></i>
            <span class="count">3</span> <!-- This should be dynamically populated -->
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
                Biscuits
            </span>
        </li>

        <li>
            <span class="breadcrumb__link">
                >
            </span>
        </li>

        <li>
            <span class="breadcrumb__link">
                Bread Stix - Blue
            </span>
        </li>
    </ul>
</section>

<!--==================== DETAILS ====================-->
<section class="details section--lg">
    <div class="details__container container grid">
        <div class="details__group">
            <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Bread Stix - Blue" class="details__img">

            <div class="details__small-images grid">
                <img src="{{ asset('assets/img/BISCUITS-2.png') }}" alt="" class="details__small-img">
                <img src="{{ asset('assets/img/BISCUITS-3.png') }}" alt="" class="details__small-img">
                <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="" class="details__small-img">
            </div>
        </div>

        <div class="details__group">
            <h3 class="details__title">Bread Stix - Blue</h3>
            <p class="details__brand">Brand: <span>Nissin</span></p>

            <div class="details__price flex">
                <span class="new__price">₱ 7.00</span>
                <span class="old__price">₱ 9.00</span>
                <span class="save__price">₱ 2.00 Off</span>
            </div>

            <div class="short__description">
                Nissin Bread Stix are crunchy, baked breadsticks that serve as a light and savory snack. 
                They have a mildly salty and buttery flavor, making them perfect for on-the-go snacking or as a pairing with dips and spreads. 
                Their crisp texture and simple, satisfying taste make them a versatile and popular choice for snack lovers.
            </div>

            <ul class="product__list">
                <li class="list__item flex">
                    <i class='bx bx-crown'></i> siguro umiral ang kadmunyuhan ng aking kamay, gumalaw
                </li>

                <li class="list__item flex">
                    <i class='bx bx-refresh' ></i> Return Policy
                </li>
            </ul>

            <div class="details__action">
                <input type="number" class="quantity" value="1">

                <a href="#" class="btn btn--sm">Add to Cart</a>

                <a href="#" class="details__action-btn">
                    <i class='bx bx-heart' ></i>
                </a>
            </div>

            <ul class="details__meta">
                <li class="meta__list flex">
                    <span>Availability: </span> 10 Items in Stock
                </li>
            </ul>
        </div>
    </div>
</section>

@include('home.footer')