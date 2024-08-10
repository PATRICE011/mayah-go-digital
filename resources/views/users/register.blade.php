@extends('home.layout')
@section('content')
@include('home.header')
@include('home.search')
@include('home.cartinside')

<div class="login show-login" id="login">
    <form action="{{ route('users.makereg') }}" method="POST" class="login__form">
        @csrf
        <h2 class="login__title">Register</h2>

        <div class="login__group">

            <div>
                <label for="name" class="login__label">Name</label>
                @error('name')
                     <p style="color: red;">{{ $message }}</p>
                @enderror
                <input type="text" placeholder="Write your Name" id="name" name="name" class="login__input" value="{{ old('name') }}" required>
            </div>

            <div>
                <label for="mobile" class="login__label">Mobile Number</label>
                @error('mobile')
                     <p style="color: red;">{{ $message }}</p>
                @enderror
                <input type="tel" placeholder="Write your Mobile Number" id="mobile" name="mobile" class="login__input" value="{{ old('mobile') }}" required>
            </div>

            <!-- <div>
                <label for="address" class="login__label">Address</label>
                @error('address')
                     <p style="color: red;">{{ $message }}</p>
                @enderror
                <input type="text" placeholder="Write your Address" id="address" name="address" class="login__input" value="{{ old('address') }}" required>
            </div> -->
            
            <div>
                <label for="password" class="login__label">Password</label>
                @error('password')
                     <p style="color: red;">{{ $message }}</p>
                @enderror
                <input type="password" placeholder="Enter your password" id="password" name="password" class="login__input" required>
            </div>
            
            <div>
                <label for="password_confirmation" class="login__label">Confirm Password</label>
                @error('password_confirmation')
                     <p style="color: red;">{{ $message }}</p>
                @enderror
                <input type="password" placeholder="Confirm your password" id="password_confirmation" name="password_confirmation" class="login__input" required>
            </div>
            
        </div>

        <div>
            <p class="login__signup">
                Already have an account? <a href="login.php">Sign in</a>
            </p>

            <button type="submit" class="login__button">Register</button>
        </div>
    </form>

    <i class="ri-close-line login__close" id="login-close"></i>
</div>

@include('home.main')
@include('home.footer')
<script src="assets/js/register.js"></script>
@endsection
