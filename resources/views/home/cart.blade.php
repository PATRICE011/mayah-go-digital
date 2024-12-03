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
               <a href="{{route('home.myaccount')}}" class="nav__link">MY ACCOUNT</a>
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
            <a href="{{route('home.shop')}}" class="breadcrumb__link">
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

         <tr>
            <td>
               <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="" class="table__img">
            </td>

            <td>
               <h2 class="table__title">
                  Bread Stix - Blue
               </h2>

               <p class="table__description">
                  Nissin Bread Stix are crunchy, baked breadsticks that serve as a light and savory snack.
               </p>
            </td>

            <td>
               <span class="table__price">₱ 7.00</span>
            </td>

            <td>
               <input type="number" value="1" class="quantity">
            </td>

            <td>
               <span class="table__subtotal">
                  ₱ 7.00
               </span>
            </td>

            <td>
               <i class='bx bx-trash table__trash'></i>
            </td>
         </tr>

         <tr>
            <td>
               <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="" class="table__img">
            </td>
   
            <td>
               <h2 class="table__title">
                  Bread Stix - Blue
               </h2>
   
               <p class="table__description">
                  Nissin Bread Stix are crunchy, baked breadsticks that serve as a light and savory snack.
               </p>
            </td>
   
            <td>
               <span class="table__price">₱ 7.00</span>
            </td>
   
            <td>
               <input type="number" value="1" class="quantity">
            </td>
   
            <td>
               <span class="table__subtotal">
                  ₱ 7.00
               </span>
            </td>
   
            <td>
               <i class='bx bx-trash table__trash'></i>
            </td>
         </tr>

         <tr>
            <td>
               <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="" class="table__img">
            </td>
   
            <td>
               <h2 class="table__title">
                  Bread Stix - Blue
               </h2>
   
               <p class="table__description">
                  Nissin Bread Stix are crunchy, baked breadsticks that serve as a light and savory snack.
               </p>
            </td>
   
            <td>
               <span class="table__price">₱ 7.00</span>
            </td>
   
            <td>
               <input type="number" value="1" class="quantity">
            </td>
   
            <td>
               <span class="table__subtotal">
                  ₱ 7.00
               </span>
            </td>
   
            <td>
               <i class='bx bx-trash table__trash'></i>
            </td>
         </tr>
      </table>
   </div>

   <div class="cart__actions">
      <a href="{{route('home.shop')}}" class="btn flex btn--md">
         <i class='bx bx-shopping-bag' ></i> Continue Shopping
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
                        <i class='bx bx-shuffle' ></i> Apply
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
                     ₱ 7.00
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
                     ₱ 7.00
                  </span>
               </td>
            </tr>
         </table>

         <a href="{{route('home.checkout')}}" class="btn flex btn--md">
            <i class='bx bx-package' ></i> Proceed to Checkout
         </a>
      </div>
   </div>
</section>

@include('home.footer')