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