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
               <a href="{{url('/shop')}}" class="nav__link active-link">SHOP</a>
            </li>

            <li class="nav__item">
               <a href="{{url('/myaccount')}}" class="nav__link">MY ACCOUNT</a>
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
         <tr class="table__row">
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Stock Status</th>
            <th>Action</th>
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
               <span class="table__stock">In Stock</span>
            </td>

            <td>
               <a href="" class="btn btn btn--sm">Add to Cart</a>
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
               <span class="table__stock">In Stock</span>
            </td>

            <td>
               <a href="" class="btn btn btn--sm">Add to Cart</a>
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
               <span class="table__stock">In Stock</span>
            </td>

            <td>
               <a href="" class="btn btn btn--sm">Add to Cart</a>
            </td>
   
            <td>
               <i class='bx bx-trash table__trash'></i>
            </td>
         </tr>
      </table>
   </div>
</section>