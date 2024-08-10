@extends('home.layout')

@section('content')
    @include('home.header')
    @include('home.search')
    @include('home.cart')

    <div class="login show-login" id="login">
        <form action="{{ route('users.verifyOtp') }}" method="POST" class="login__form">
            @csrf
            <h2 class="login__title">OTP Verification</h2>

            @if(session('error'))
                <p style="color: red;">{{ session('error') }}</p>
            @endif

            @if(session('success'))
                <p style="color: green;">{{ session('success') }}</p>
            @endif

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
                <label for="otp" class="login__label">OTP</label>
                <input type="text" placeholder="Enter your One-Time Password" id="otp" name="otp" class="login__input" required>
            </div>

            <button type="submit" class="login__button">Submit</button>
            
            <!-- Resend OTP Link -->
            <p class="login__resend">
                Didn't receive the OTP? <a href="#" onclick="event.preventDefault(); document.getElementById('resend-otp-form').submit();">Resend OTP</a>
            </p>
        </form>

        <!-- Hidden Form to Resend OTP -->
        <form id="resend-otp-form" action="{{ route('users.resendOtp') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    @include('home.main')
    @include('home.footer')
    <script src="assets/js/login.js"></script>
@endsection
