@extends('home.layout')
@section('title','Mayah Store - Register')

<header class="header" id="header">
    <!-- Header content here -->
</header>

@section('content')

<!--==================== BREADCRUMB ====================-->
<section class="breadcrumb">
    <ul class="breadcrumb__list flex container">
        <li><a href="{{ url('/') }}" class="breadcrumb__link">Home</a></li>
        <li><span class="breadcrumb__separator">&gt;</span></li>
        <li><a href="{{ url('user/register') }}" class="breadcrumb__link">Register</a></li>
    </ul>
</section>

<!--==================== REGISTER ====================-->
<section class="login-register section--lg">
    <div class="login-register__container container grid">
        <div class="register">
            <h3 class="section__title">Register</h3>

            <!-- Display errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ url('user/register') }}" method="POST" class="form grid">
                @csrf
                
                <label for="name" class="login-register__label">Name</label>
                <input type="text" name="name" id="name" placeholder="Enter your Name" class="form__input">
                @if ($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif

                <label for="phone" class="login-register__label">Phone Number</label>
                <input type="tel" name="mobile" id="phone" placeholder="Enter your Phone Number" class="form__input">
                @if ($errors->has('mobile'))
                    <span class="text-danger">{{ $errors->first('mobile') }}</span>
                @endif

                <label for="password" class="login-register__label">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your Password" class="form__input">
                @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif

                <label for="password_confirmation" class="login-register__label">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" class="form__input">
                @if ($errors->has('password_confirmation'))
                    <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                @endif

                <div>
                    <p class="login__signup">
                        Already have an account? <a href="{{ url('user/login') }}" class="login-register__link">Sign In</a>
                    </p>
                </div>

                <div class="form__btn">
                    <button type="submit" class="btn">Register</button>
                </div>
            </form>
        </div>
    </div>
</section>

@include('home.footer')

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