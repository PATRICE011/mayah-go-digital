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
                
                <input type="text" placeholder="Write your Name" id="name" name="name" class="login__input" value="{{ old('name') }}" required>
            </div>

            <div>
                <label for="mobile" class="login__label">Mobile Number</label>
                
                <input type="tel" placeholder="Write your Mobile Number" id="mobile" name="mobile" class="login__input" value="{{ old('mobile') }}" required>
            </div>

           
            
            <div>
                <label for="password" class="login__label">Password</label>
                
                <input type="password" placeholder="Enter your password" id="password" name="password" class="login__input" required>
            </div>
            
            <div>
                <label for="password_confirmation" class="login__label">Confirm Password</label>
                
                <input type="password" placeholder="Confirm your password" id="password_confirmation" name="password_confirmation" class="login__input" required>
            </div>
            
        </div>

        <div>
            <p class="login__signup">
                Already have an account? <a href="{{route('users.login')}}">Sign in</a>
            </p>

            <button type="submit" class="login__button">Register</button>
        </div>
    </form>

    <i class="ri-close-line login__close" id="login-close"></i>
</div>

@include('home.main')
@include('home.footer')
<script src="assets/js/register.js"></script>

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

    @if ($errors->any())
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
        };

        @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach
    </script>
@endif
@endsection
