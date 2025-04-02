@extends('home.layout')
@section('title','Mayah Store - Register')

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
            <i class='bx bx-heart'></i><span class="count">0</span>
         </a>

         <a href="{{ url('/cart') }}" class="header__action-btn">
            <i class='bx bx-cart-alt'></i><span id="cart-count" class="count">0</span>
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
      <li>
         <a href="{{ url('/') }}" class="breadcrumb__link">Home</a>
      </li>

      <li>
         <span class="breadcrumb__separator">&gt;</span>
      </li>

      <li>
         <a href="{{ url('user/register') }}" class="breadcrumb__link">Register</a>
      </li>
   </ul>
</section>

<!--==================== REGISTER ====================-->
<section class="login-register section--lg">
   <div class="login-register__container container grid">
      <div class="register">
         <h3 class="section__title">Register</h3>


         <form action="{{ url('user/register') }}" method="POST" class="form grid">
            @csrf

            <label for="name" class="login-register__label">Name</label>
            <input type="text" name="name" id="name" placeholder="Enter your Name" class="form__input">
            @if ($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
            @endif

            <label for="phone" class="login-register__label">Phone Number</label>
            <input type="tel" name="mobile" id="phone" placeholder="Enter your Phone Number" class="form__input">
            @if ($errors->has('mobile'))
            <span class="text-danger">{{ $errors->first('mobile') }}</span>
            @endif

            <label for="password" class="login-register__label">Password</label>
            <input type="password" name="password" id="password" placeholder="Enter your Password" class="form__input">
            @if ($errors->has('password'))
            <span class="text-danger">{{ $errors->first('password') }}</span>
            @endif

            <label for="password_confirmation" class="login-register__label">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" class="form__input">
            @if ($errors->has('password_confirmation'))
            <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
            @endif

            <div>
               <p class="login__signup">
                  Already have an account? <a href="{{ url('user/login') }}" class="login-register__link">Sign In</a>
               </p>
            </div>

            <div class="form__btn">
               <button type="submit" class="btn">Register</button>
            </div>
         </form>
      </div>
   </div>
</section>

@include('home.footer')

@endsection
<script>
   toastr.options = {
      "closeButton": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "timeOut": "5000",
   };

   // Display success message if available
   @if(session('message'))
   toastr.success("{{ session('message') }}");
   @endif

   // Display error message if available
   @if(session('error'))
   toastr.error("{{ session('error') }}");
   @endif
</script>