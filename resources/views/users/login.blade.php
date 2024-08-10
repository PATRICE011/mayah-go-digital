@extends('home.layout')

@section('content')
<!-- header -->
    @include('home.header')
    @include('home.search')
    @include('home.cartinside')
    <div class="login show-login" id="login">
        <form action="{{ route('login') }}" method="POST" class="login__form">
            @csrf
            <h2 class="login__title">Log In</h2>

            <!-- Display error messages if there are any -->
            @if ($errors->any())
                <div style="color: red;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="login__group">
                <div>
                    <label for="mobile" class="login__label">Phone Number</label>
                    <input type="tel" placeholder="Write your phone number" id="mobile" name="mobile" class="login__input" required>
                </div>

                <div>
                    <label for="password" class="login__label">Password</label>
                    <input type="password" placeholder="Enter your password" id="password" name="password" class="login__input" required>
                </div>
            </div>

            <div>
                <p class="login__signup">
                    Do not have an account? <a href="{{ route('users.register') }}">Sign up</a>
                </p>

                <button type="submit" class="login__button">Log In</button>
            </div>
        </form>

        <i class="ri-close-line login__close" id="login-close"></i>
    </div>
    <!-- end of header -->
    
    @include('home.main')
    @include('home.footer')
    <script src="assets/js/login.js"></script>
@endsection
