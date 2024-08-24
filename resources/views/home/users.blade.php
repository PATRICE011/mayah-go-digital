<div class="login" id="login">
    @guest
    <form action="" class="login__form">
        <button type="button" class="login__button">
            <a href="{{route('users.register')}}">
                Register
            </a>
        </button>

        <button type="button" class="login__button" >
            <a href="{{route('users.login')}}">
                Login
            </a>
        </button>
    </form>
    @endguest

    <!-- User actions -->
    @auth
    <form method="POST" action="{{ route('users.logout') }}" class="nav__logout-form login__form">
        <p class="nav__user-name">Welcome, {{ Auth::user()->name }}</p>    
        @csrf
        <button type="submit" class="button-1 button__ghost">Logout</button>
    </form>
    @endauth

    <i class="ri-close-line login__close" id="login-close"></i>
</div>