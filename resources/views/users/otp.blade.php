@extends('home.layout')

@section('content')
    @include('home.header')
    @include('home.search')
    @include('home.cartinside')

    <div class="login show-login" id="login">
        <form action="{{ route('users.verifyOtp') }}" method="POST" class="login__form">
            @csrf
            <h2 class="login__title">OTP Verification</h2>

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
            
            <p class="login__resend">
                Didn't receive the OTP? <a href="#" id="resend-link" onclick="event.preventDefault(); if(!this.classList.contains('disabled')) { document.getElementById('resend-otp-form').submit(); }">Resend OTP</a>
            </p>

        </form>

        <form id="resend-otp-form" action="{{ route('users.resendOtp') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    @include('home.main')
    @include('home.footer')

    <!-- Custom scripts after Toastr -->

    @section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var resendLink = document.getElementById('resend-link');
        var timer = 60; // Timer set for 60 seconds

        // Disable the link initially
        resendLink.classList.add('disabled');
        resendLink.style.pointerEvents = 'none';

        var interval = setInterval(function() {
            if (timer <= 0) {
                clearInterval(interval);
                resendLink.textContent = 'Resend OTP';
                resendLink.classList.remove('disabled');
                resendLink.style.pointerEvents = 'auto';
            } else {
                resendLink.textContent = 'Please wait ' + timer + ' seconds';
                timer--;
            }
        }, 1000);
    });
</script>
@endsection

   
    @if (Session::has('message'))
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
        };

        toastr.success("{{ Session::get('message') }}");
    </script>
    @endif

    @if (Session::has('error'))
        <script>
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000",
            };

            toastr.error("{{ Session::get('error') }}");
        </script>
    @endif


@endsection
