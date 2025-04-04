@extends('home.layout')
@section('title','Mayah Store - Shop')

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
      @auth

      <div class="nav__menu" id="nav-menu">
         <div class="nav__menu-top">
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

            <div class="nav__close" id="nav-close">
               <i class="ri-close-line"></i>
            </div>
         </div>
         <ul class="nav__list">
            @auth
            <a href="{{url('/user')}}" class="breadcrumb__link">
               HOME
            </a>
            @else
            <a href="{{url('/')}}" class="breadcrumb__link">
               HOME
            </a>
            @endauth

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

<section class="breadcrumb">
   <ul class="breadcrumb__list flex container">
      <li><a href="{{ url(Auth::check() ? '/user' : '/') }}" class="breadcrumb__link">Home</a></li>
      <li><span class="breadcrumb__link">></span></li>
      <li><span class="breadcrumb__link">Shop</span></li>
   </ul>
</section>

<section class="products section--lg container">

   <p class="total__products">
      We found <span>{{ $totalProducts ?? 0 }}</span> items for you!
   </p>

   <div class="products__layout">
      <div class="product__categories-sidebar">
         <div class="product__category-section">
            <h3>Categories</h3>
            <ul>
               @foreach($categories as $category)
               <li>
                  <input type="checkbox" id="{{ $category->slug }}" value="{{ $category->category_name }}" class="brand-filter">
                  <label for="{{ $category->slug }}">{{ $category->category_name }}</label>
               </li>
               @endforeach
            </ul>
         </div>
      </div>

      <div class="products__grid">
         <div class="products__container grid" id="productsContainer">
            @include('home.partials.product_grid', ['products' => $products ?? collect()])
         </div>
         <div id="paginationLinks">
            @include('home.partials.pagination_links', ['products' => $products])
         </div>
      </div>
   </div>
</section>

@include('home.footer')

@section('scripts')
<script>
   $(function() {
      // Handle search
      $('#searchInput').on('keyup', function() {
         let query = $(this).val().trim();
         fetchProducts('?search=' + encodeURIComponent(query));
      });

      // Handle pagination
      $(document).on('click', '.pagination a', function(event) {
         event.preventDefault();
         let url = $(this).attr('href');
         fetchProducts(url);
      });

      function fetchProducts(url) {
         $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
               if (response.products) {
                  $('#productsContainer').html(response.products);
               }
               if (response.pagination) {
                  $('#paginationLinks').html(response.pagination);
               }
            },
            error: function(xhr, status, error) {
               console.error(error);
            }
         });
      }
   });
</script>
@endsection
@endsection