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
      @endauth

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
               <a href="{{url('/')}}" class="nav__link active-link">HOME</a>
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
<section class="login-register section--lg">
   <div class="login-register__container container grid">
      <div class="register">
         <h3 class="section__title">Register</h3>
         <!-- Error Messages Section -->

         <div class="error-messages">
            <span id="name-error" class="text-danger"></span>
            @if ($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
            @endif

            <span id="mobile-error" class="text-danger"></span>
            @if ($errors->has('mobile'))
            <span class="text-danger">{{ $errors->first('mobile') }}</span>
            @endif

            <span id="password-error" class="text-danger"></span>
            @if ($errors->has('password'))
            <span class="text-danger">{{ $errors->first('password') }}</span>
            @endif

            <span id="password-confirmation-error" class="text-danger"></span>
            @if ($errors->has('password_confirmation'))
            <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
            @endif
         </div>
         <!-- <hr class="divider"> -->
         <form id="registerForm" action="{{ url('user/register') }}" method="POST" class="form grid">
            @csrf

            <label for="name" class="login-register__label">Name</label>
            <input type="text" name="name" id="name" placeholder="Full Name (e.g., John Doe)" class="form__input" aria-label="Enter your full name">

            <label for="phone" class="login-register__label">Phone Number</label>
            <input type="tel" name="mobile" id="mobile" placeholder="Enter your Phone Number (e.g., 09123456789)" class="form__input">

            <label for="password" class="login-register__label">Password</label>
            <input type="password" name="password" id="password" placeholder="Enter your Password" class="form__input">

            <label for="password_confirmation" class="login-register__label">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" class="form__input">

            <div>
               <p class="login__signup">
                  Already have an account? <a href="{{ url('user/login') }}" class="login-register__link">Sign In</a>
               </p>
            </div>

            <div class="form__btn">
               <button type="submit" id="registerBtn" class="btn">Register</button>
            </div>
         </form>
      </div>
   </div>
</section>

@include('home.footer')

@endsection
@section('styles')
<style>
   /* Divider style */
   .divider {
      border-top: 1px solid #ddd;
      margin: 20px 0;
   }

   /* Error message container */
   .error-messages {
      font-size: 14px;
      color: red;
      margin-top: 10px;
   }

   /* You can also style the individual error messages further if needed */
   .text-danger {
      display: block;
      margin-bottom: 5px;
   }
</style>
@endsection
@section('scripts')
<script>
   document.addEventListener('DOMContentLoaded', function() {
      // Get form and input elements
      const registerForm = document.getElementById('registerForm');
      const nameInput = document.getElementById('name');
      const mobileInput = document.getElementById('mobile');
      const passwordInput = document.getElementById('password');
      const confirmPasswordInput = document.getElementById('password_confirmation');

      // Get error elements
      const nameError = document.getElementById('name-error');
      const mobileError = document.getElementById('mobile-error');
      const passwordError = document.getElementById('password-error');
      const confirmPasswordError = document.getElementById('password-confirmation-error');

      // Validation functions
      function validateName() {
         const nameValue = nameInput.value.trim();
         // Only letters and spaces allowed
         const nameRegex = /^[a-zA-Z\s]*$/;

         if (!nameValue) {
            nameError.textContent = 'Name is required';
            return false;
         } else if (!nameRegex.test(nameValue)) {
            nameError.textContent = 'Name can only contain letters and spaces';
            return false;
         } else if (nameValue.length > 255) {
            nameError.textContent = 'Name must be less than 255 characters';
            return false;
         }

         nameError.textContent = '';
         return true;
      }

      function validateMobile() {
         const mobileValue = mobileInput.value.trim();
         // Philippine mobile number format (09XXXXXXXXX or +639XXXXXXXXX)
         const mobileRegex = /^(09\d{9}|(\+639)\d{9})$/;

         if (!mobileValue) {
            mobileError.textContent = 'Phone number is required';
            return false;
         } else if (!mobileRegex.test(mobileValue)) {
            mobileError.textContent = 'Please enter a valid Philippine mobile number (09XXXXXXXXX or +639XXXXXXXXX)';
            return false;
         }

         mobileError.textContent = '';
         return true;
      }

      function validatePassword() {
         const passwordValue = passwordInput.value;

         if (!passwordValue) {
            passwordError.textContent = 'Password is required';
            return false;
         } else if (passwordValue.length < 8) {
            passwordError.textContent = 'Password must be at least 8 characters long';
            return false;
         }

         passwordError.textContent = '';
         return true;
      }

      function validateConfirmPassword() {
         const passwordValue = passwordInput.value;
         const confirmValue = confirmPasswordInput.value;

         if (!confirmValue) {
            confirmPasswordError.textContent = 'Please confirm your password';
            return false;
         } else if (passwordValue !== confirmValue) {
            confirmPasswordError.textContent = 'Passwords do not match';
            return false;
         }

         confirmPasswordError.textContent = '';
         return true;
      }

      // Add validation event listeners
      nameInput.addEventListener('blur', validateName);
      mobileInput.addEventListener('blur', validateMobile);
      passwordInput.addEventListener('blur', validatePassword);
      confirmPasswordInput.addEventListener('blur', validateConfirmPassword);

      // Real-time validation as user types
      nameInput.addEventListener('input', function() {
         if (nameInput.value.trim() !== '') validateName();
      });

      mobileInput.addEventListener('input', function() {
         if (mobileInput.value.trim() !== '') validateMobile();
      });

      passwordInput.addEventListener('input', function() {
         if (passwordInput.value !== '') validatePassword();
         if (confirmPasswordInput.value !== '') validateConfirmPassword();
      });

      confirmPasswordInput.addEventListener('input', function() {
         if (confirmPasswordInput.value !== '') validateConfirmPassword();
      });

      // Form submission validation
      registerForm.addEventListener('submit', function(event) {
         // Validate all fields before submission
         const isNameValid = validateName();
         const isMobileValid = validateMobile();
         const isPasswordValid = validatePassword();
         const isConfirmPasswordValid = validateConfirmPassword();

         // Prevent form submission if any validation fails
         if (!isNameValid || !isMobileValid || !isPasswordValid || !isConfirmPasswordValid) {
            event.preventDefault();
         }
      });
   });
</script>
@endsection