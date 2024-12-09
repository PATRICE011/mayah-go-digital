@extends('home.layout')
@section('title','Mayah Store - Wishlist')

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
               <a href="{{url('/')}}" class="nav__link">HOME</a>
            </li>

            <li class="nav__item">
               <a href="{{url('/shop')}}" class="nav__link active-link">SHOP</a>
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
         <a href="{{route('home.wishlist')}}" class="breadcrumb__link">
            Wishlist
         </a>
      </li>
   </ul>
</section>

<!--==================== WISHLIST ====================-->
<section class="wishlist section--lg container">
   <div class="table__container">
      <table class="table">
         <thead>
            <tr class="table__row">
               <th>Image</th>
               <th>Name</th>
               <th>Price</th>
               <th>Stock Status</th>
               <th>Action</th>
               <th>Remove</th>
            </tr>
         </thead>

         <tbody>
            @forelse ($wishlistItems as $wishlistItem)
            <tr>
               <td>
                  <img src="{{ asset('assets/img/'.$wishlistItem->product->product_image) }}" alt="{{ $wishlistItem->product->product_name }}" class="table__img">
               </td>

               <td>
                  <h2 class="table__title">{{ $wishlistItem->product->product_name }}</h2>
                  <p class="table__description">{{ $wishlistItem->product->product_description }}</p>
               </td>

               <td>
                  <span class="table__price">₱ {{ number_format($wishlistItem->product->product_price, 2) }}</span>
               </td>

               <td>
                  @if ($wishlistItem->product->product_stocks > 0)
                  <span class="table__stock">In Stock</span>
                  @else
                  <span class="table__stock">Out of Stock</span>
                  @endif
               </td>

               <td>
                  @if ($wishlistItem->product->product_stocks > 0)
                  <a href="{{ route('home.inserttocart', $wishlistItem->product->id) }}" class="btn btn--sm">Add to Cart</a>
                  @else
                  <button class="btn btn--sm" disabled>Out of Stock</button>
                  @endif
               </td>

               <td>
                  <form id="destroy-button" action="{{ route('wishlist.remove',$wishlistItem->id) }}" method="POST">
                     @csrf
                     @method('DELETE')
                  </form>
                  <i class='bx bx-trash table__trash' onclick="document.getElementById('destroy-button').submit();"></i>
               </td>
            </tr>
            @empty
            <tr>
               <td colspan="6" class="text-center">Your wishlist is empty.</td>
            </tr>
            @endforelse
         </tbody>
      </table>
   </div>
</section>
@include('home.footer')