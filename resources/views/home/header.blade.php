<header class="header" id="header">
   <nav class="nav container">
      <a href="{{route('home.index')}}" class="nav__logo">
          <!-- <img src="assets/img/MAYAH-STORE-LOGO.jpg" alt="" class="nav__logo-img"> -->
          <i class="ri-leaf-line nav__logo-icon"></i>
          Mayah Store
      </a>

      <div class="nav__menu" id="nav-menu">
         <ul class="nav__list">
            <li class="nav__item">
               <a href="{{route('home.index')}}" class="nav__link active-link">Home</a>
            </li>
            
            <li class="nav__item">
               <a href="#about" class="nav__link">About</a>
            </li>

            <li class="nav__item">
               <a href="#products" class="nav__link">Products</a>
            </li>

            <li class="nav__item">
               <a href="#faqs" class="nav__link">FAQs</a>
            </li>
            
            <li class="nav__item">
               <a href="#faqs" class="nav__link">
                  <!-- Login button -->
                  <!-- <i class="ri-user-line nav__login" id="login-btn"></i> User -->
               </a>
            </li>
         </ul>

         <!-- Close button -->
         <div class="nav__close" id="nav-close">
            <i class="ri-close-line"></i>
         </div>
      </div>

      <div class="nav__btns">
         <!-- Theme change button -->
         <i class="ri-moon-line change-theme" id="theme-button"></i>

         <!-- Cart button -->
         <div class="nav_shop" id="cart-shop">
             <i class="ri-shopping-cart-2-line nav__shop"></i>
         </div>

         <!-- Search button -->
         <i class="ri-search-line nav__search" id="search-btn"></i>

         <div class="nav__toggle" id="nav-toggle">
             <i class="ri-menu-line"></i>
         </div>
      </div>
   </nav>
</header>