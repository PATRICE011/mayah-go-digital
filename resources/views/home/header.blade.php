
<header class="header" id="header">
         <nav class="nav container">
            <a href="index.php" class="nav__logo">
                <img src="assets/img/MAYAH-STORE-LOGO.jpg" alt="" class="nav__logo-img">
                Mayah Store
            </a>

            <div class="nav__menu" id="nav-menu">
               <ul class="nav__list">
                  <li class="nav__item">
                     <a href="{{route('home.index')}}" class="nav__link active-link">Home</a>
                  </li>

                  <li class="nav__item">
                     <a href="#about" class="nav__link">About Us</a>
                  </li>

                  <li class="nav__item">
                     <a href="#products" class="nav__link">Products</a>
                  </li>

                  <li class="nav__item">
                     <a href="#" class="nav__link"></a>
                  </li>
               </ul>

               <!-- Close button -->
               <div class="nav__close" id="nav-close">
                  <i class="ri-close-line"></i>
               </div>
            </div>

            <div class="nav__actions">
               <!-- Search button -->
               <i class="ri-search-line nav__search" id="search-btn"></i>

               <!-- Cart button -->
                <div class="nav_shop" id="cart-shop">
                    <i class="ri-shopping-cart-2-line nav__shop"></i>
                    
                </div>
               <!-- Login button -->
               <i class="ri-user-line nav__login" id="login-btn"></i>
               
               <!-- Toggle button -->
               <div class="nav__toggle" id="nav-toggle">
                  <i class="ri-menu-line"></i>
               </div>
            </div>
         </nav>
      </header>