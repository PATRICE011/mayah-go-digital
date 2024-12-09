@extends('home.layout')
@section('title','Mayah Store - Cart')

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
               <button type="submit" class="header__top-action-btn">Logout</button>
            </form>
            @endauth

            <span> / </span>
            <span class="header__top-action">
               Welcome, <span>{{ Auth::user()->name }}</span>!
            </span>
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
               <a href="{{url('user/myaccount')}}" class="nav__link">MY ACCOUNT</a>
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
         <a href="{{url('/cart')}}" class="breadcrumb__link">
            Home
         </a>
      </li>

      <li>
         <span class="breadcrumb__link">
            >
         </span>
      </li>

      <li>
         <a href="{{url('/shop')}}" class="breadcrumb__link">
            Shop
         </a>
      </li>

      <li>
         <span class="breadcrumb__link">
            >
         </span>
      </li>

      <li>
         <span class="breadcrumb__link">
            Cart
         </span>
      </li>
   </ul>
</section>

<!--==================== CART ====================-->
<section class="cart section--lg container">
   @if($cartItems->isEmpty())
   <!-- If cart is empty -->
   <div class="empty-cart-message">
      <h2>Your cart is empty!</h2>
      <p>Looks like you haven't added anything to your cart yet. Start shopping now.</p>
      <a href="{{ url('/shop') }}" class="btn flex btn--md">
         <i class='bx bx-shopping-bag'></i> Continue Shopping
      </a>
   </div>
   @else
   <!-- If cart has items -->
   <div class="table__container">
      <table class="table">
         <tr class="table__row">
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Remove</th>
         </tr>

         @foreach ($cartItems as $cartItem)
         <tr>
            <td>
               <img src="{{ asset('assets/img/'.$cartItem->product->product_image) }}" alt="{{ $cartItem->product->name }}" class="table__img">
            </td>

            <td>
               <h2 class="table__title">{{ $cartItem->product->product_name }}</h2>
               <p class="table__description">{{ $cartItem->product->product_description }}</p>
            </td>

            <td>
               <span class="table__price">₱ {{ number_format($cartItem->product->product_price, 2) }}</span>
            </td>

            <td>
               <input type="number" value="{{ $cartItem->quantity }}" class="quantity" data-id="{{ $cartItem->id }}">
            </td>

            <td>
               <span class="table__subtotal">₱ {{ number_format($cartItem->product->product_price * $cartItem->quantity, 2) }}</span>
            </td>

            <td>
               <form id="destroy-button-{{ $cartItem->id }}" action="{{ route('cartDestroy', $cartItem->id) }}" method="POST">
                  @csrf
                  @method('DELETE')
               </form>
               <i class='bx bx-trash table__trash' onclick="document.getElementById('destroy-button-{{ $cartItem->id }}').submit();"></i>
            </td>

         </tr>
         @endforeach
      </table>
   </div>

   <div class="cart__actions">
      <a href="{{ url('/shop') }}" class="btn flex btn--md">
         <i class='bx bx-shopping-bag'></i> Continue Shopping
      </a>
   </div>

   <div class="divider">
      <i class='bx bx-smile'></i>
   </div>

   <div class="cart__group grid">
      <div>
         <div class="cart__coupon">
            <h3 class="section__title">Apply Coupon</h3>

            <form action="" class="coupon__form form grid">
               <div class="form__group grid">
                  <input type="text" placeholder="Enter your coupon" class="form__input">

                  <div class="form__btn">
                     <button class="btn flex btn--sm">
                        <i class='bx bx-shuffle'></i> Apply
                     </button>
                  </div>
               </div>
            </form>
         </div>
      </div>

      <div class="cart__total">
         <h3 class="section__title">Cart Total</h3>

         <table class="cart__total-table">
            <tr>
               <td>
                  <span class="cart__total-title">
                     Subtotal
                  </span>
               </td>

               <td>
                  <span class="cart__total-price">
                     ₱ {{ number_format($cartItems->sum(function ($item) { return $item->quantity * $item->product->price; }), 2) }}
                  </span>
               </td>
            </tr>

            <tr>
               <td>
                  <span class="cart__total-title">
                     Discount
                  </span>
               </td>

               <td>
                  <span class="cart__total-price">
                     ₱ 0.00
                  </span>
               </td>
            </tr>

            <tr>
               <td>
                  <span class="cart__total-title">
                     Total
                  </span>
               </td>

               <td>
                  <span class="cart__total-price">
                     ₱ {{ number_format($cartItems->sum(function ($item) { return $item->quantity * $item->product->price; }), 2) }}
                  </span>
               </td>
            </tr>
         </table>

         <a href="{{ url('/checkout') }}" class="btn flex btn--md">
            <i class='bx bx-package'></i> Proceed to Checkout
         </a>
      </div>
   </div>
   @endif
</section>


@include('home.footer')

@endsection