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
                Didn't receive the OTP? <a href="#" id="resendLink" onclick="startTimer(60, this)">Resend OTP</a>
                <span id="timer" style="display:none;">Please wait for 60 seconds to resend OTP.</span>
            </p>

        </form>

        <form id="resend-otp-form" action="{{ route('users.resendOtp') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    @include('home.main')
    @include('home.footer')

    <!-- Custom scripts after Toastr -->
   
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

    <script>
        function startTimer(duration, linkElement) {
            var timer = duration, minutes, seconds;
            linkElement.style.display = 'none';
            var timerSpan = document.getElementById('timer');
            timerSpan.style.display = '';

            var interval = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                seconds = seconds < 10 ? "0" + seconds : seconds;

                timerSpan.textContent = 'Please wait ' + minutes + ":" + seconds + ' seconds to resend OTP.';

                if (--timer < 0) {
                    timer = duration;
                    clearInterval(interval);
                    linkElement.style.display = '';
                    timerSpan.style.display = 'none';
                    document.getElementById('resend-otp-form').submit();
                }
            }, 1000);
        }
    </script>

@endsection