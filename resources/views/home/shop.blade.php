@extends('home.layout')
@section('title','Mayah Store - Shop')

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
        <a href="{{url('/')}}" class="nav__logo">
            <i class="ri-restaurant-2-fill nav__logo-icon"></i> Mayah Store
        </a>

        <div class="nav__menu" id="nav-menu">
            <ul class="nav__list">
                <li class="nav__item">
                    <a href="{{url('/')}}" class="nav__link">HOME</a>
                </li>

                <li class="nav__item">
                    <a href="{{url('/shop')}}" class="nav__link active-link">SHOP</a>
                </li>

                @auth
                <li class="nav__item">
                    <a href="{{url('/user/myaccount')}}" class="nav__link">MY ACCOUNT</a>
                </li>
                @endauth
            </ul>

            <div class="header__search">
                <input type="text" placeholder="Search Item" class="form__input">

                <button class="search__btn">
                    <i class='bx bx-search search'></i>
                </button>
            </div>
        </div>

        <div class="header__user-actions">
            <a href="{{url('/wishlist')}}" class="header__action-btn">
                <i class='bx bx-heart'></i>
                <span class="count">0</span>
            </a>

            <a href="{{url('/cart')}}" class="header__action-btn">
                <i class='bx bx-cart-alt'></i>
                <span class="count">{{ $cartCount }}</span>
            </a>
        </div>
    </nav>
</header>

@section('content')

<!--==================== BREADCRUMB ====================-->
<section class="breadcrumb">
    <ul class="breadcrumb__list flex container">
        <li>
            <a href="{{url('/')}}" class="breadcrumb__link">
                Home
            </a>
        </li>

        <li>
            <span class="breadcrumb__link">
                >
            </span>
        </li>

        <li>
            <span class="breadcrumb__link">
                Shop
            </span>
        </li>
    </ul>
</section>

<!--==================== PRODUCTS ====================-->
<section class="products section--lg container">
    <p class="total__products">We found <span>"idk what #"</span> items for you!</p>

    <div class="products__container grid">
        @foreach($products as $product)
        <div class="product__item">
            <div class="product__banner">
                <a href="#" class="product__images">
                    <img src="{{ asset('assets/img/' . $product->product_image) }}" alt="{{ $product->product_name }}" class="product__img default">
                    <img src="{{ asset('assets/img/' . $product->product_image) }}" alt="{{ $product->product_name }}" class="product__img hover">
                </a>

                <div class="product__actions">
                    <a href="{{url('/details')}}" class="action__btn" aria-label="Quick View">
                        <i class='bx bx-expand-horizontal'></i>
                    </a>
                    <a href="#" class="action__btn" aria-label="Add To Wishlist">
                        <i class='bx bx-heart'></i>
                    </a>
                    <a href="#" class="action__btn" aria-label="Compare">
                        <i class='bx bx-shuffle'></i>
                    </a>
                </div>

                <div class="product__badge light-pink">Hot</div>
            </div>

            <div class="product__content">
                <span class="product__category">{{ $product->category_name }}</span>
                <a href="details.html">
                    <h3 class="product__title">{{ $product->product_name }}</h3>
                </a>
                <div class="product__rating">
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                </div>
                <div class="product__price flex">
                    <span class="new__price">₱ {{ number_format($product->product_price, 2) }}</span>
                    <span class="old__price">₱ 9.00</span>
                </div>
                <form action="{{ route('home.inserttocart') }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="id" value="{{ $product->id }}">
                <button type="submit" class="action__btn cart__btn" aria-label="Add To Cart">
                    <i class='bx bx-cart-alt'></i>
                </button>
            </form>

            </div>
        </div>
        @endforeach
        <div class="product__item">
            <div class="product__banner">
                <a href="detail.html" class="product__images">
                    <img src="{{ asset('assets/img/BISCUITS-2.png') }}" alt="Biscuit-2" class="product__img default">

                    <img src="{{ asset('assets/img/BISCUITS-2.png') }}" alt="Biscuit-2" class="product__img hover">
                </a>

                <div class="product__actions">
                    <a href="#" class="action__btn" aria-label="Quick View">
                        <i class='bx bx-expand-horizontal'></i>
                    </a>

                    <a href="#" class="action__btn" aria-label="Add To Wishlist">
                        <i class='bx bx-heart'></i>
                    </a>

                    <a href="#" class="action__btn" aria-label="Compare">
                        <i class='bx bx-shuffle'></i>
                    </a>
                </div>

                <div class="product__badge light-green">Hot</div>
            </div>

            <div class="product__content">
                <span class="product__category">Biscuits</span>

                <a href="details.html">
                    <h3 class="product__title">Bread Stix - Blue</h3>
                </a>

                <div class="product__rating">
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                </div>

                <div class="product__price flex">
                    <span class="new__price">₱ 7.00</span>
                    <span class="old__price">₱ 9.00</span>
                </div>

                <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                    <i class='bx bx-cart-alt'></i>
                </a>
            </div>
        </div>

        <div class="product__item">
            <div class="product__banner">
                <a href="detail.html" class="product__images">
                    <img src="{{ asset('assets/img/BISCUITS-3.png') }}" alt="Biscuit-3" class="product__img default">

                    <img src="{{ asset('assets/img/BISCUITS-3.png') }}" alt="Biscuit-3" class="product__img hover">
                </a>
                <div class="product__actions">
                    <a href="#" class="action__btn" aria-label="Quick View">
                        <i class='bx bx-expand-horizontal'></i>
                    </a>

                    <a href="#" class="action__btn" aria-label="Add To Wishlist">
                        <i class='bx bx-heart'></i>
                    </a>

                    <a href="#" class="action__btn" aria-label="Compare">
                        <i class='bx bx-shuffle'></i>
                    </a>
                </div>

                <div class="product__badge light-orange">Hot</div>
            </div>

            <div class="product__content">
                <span class="product__category">Biscuits</span>
                <a href="details.html">
                    <h3 class="product__title">Bread Stix - Blue</h3>
                </a>

                <div class="product__rating">
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                </div>

                <div class="product__price flex">
                    <span class="new__price">₱ 7.00</span>
                    <span class="old__price">₱ 9.00</span>
                </div>

                <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                    <i class='bx bx-cart-alt'></i>
                </a>
            </div>
        </div>

        <div class="product__item">
            <div class="product__banner">
                <a href="detail.html" class="product__images">
                    <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Biscuit-1" class="product__img default">

                    <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Biscuit-1" class="product__img hover">
                </a>

                <div class="product__actions">
                    <a href="#" class="action__btn" aria-label="Quick View">
                        <i class='bx bx-expand-horizontal'></i>
                    </a>
                    <a href="#" class="action__btn" aria-label="Add To Wishlist">
                        <i class='bx bx-heart'></i>
                    </a>
                    <a href="#" class="action__btn" aria-label="Compare">
                        <i class='bx bx-shuffle'></i>
                    </a>
                </div>

                <div class="product__badge light-pink">Hot</div>
            </div>

            <div class="product__content">
                <span class="product__category">Biscuits</span>
                <a href="details.html">
                    <h3 class="product__title">Bread Stix - Blue</h3>
                </a>
                <div class="product__rating">
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                </div>
                <div class="product__price flex">
                    <span class="new__price">₱ 7.00</span>
                    <span class="old__price">₱ 9.00</span>
                </div>
                <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                    <i class='bx bx-cart-alt'></i>
                </a>
            </div>
        </div>

        <div class="product__item">
            <div class="product__banner">
                <a href="detail.html" class="product__images">
                    <img src="{{ asset('assets/img/BISCUITS-2.png') }}" alt="Biscuit-2" class="product__img default">

                    <img src="{{ asset('assets/img/BISCUITS-2.png') }}" alt="Biscuit-2" class="product__img hover">
                </a>

                <div class="product__actions">
                    <a href="#" class="action__btn" aria-label="Quick View">
                        <i class='bx bx-expand-horizontal'></i>
                    </a>

                    <a href="#" class="action__btn" aria-label="Add To Wishlist">
                        <i class='bx bx-heart'></i>
                    </a>

                    <a href="#" class="action__btn" aria-label="Compare">
                        <i class='bx bx-shuffle'></i>
                    </a>
                </div>

                <div class="product__badge light-green">Hot</div>
            </div>

            <div class="product__content">
                <span class="product__category">Biscuits</span>

                <a href="details.html">
                    <h3 class="product__title">Bread Stix - Blue</h3>
                </a>

                <div class="product__rating">
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                </div>

                <div class="product__price flex">
                    <span class="new__price">₱ 7.00</span>
                    <span class="old__price">₱ 9.00</span>
                </div>

                <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                    <i class='bx bx-cart-alt'></i>
                </a>
            </div>
        </div>

        <div class="product__item">
            <div class="product__banner">
                <a href="detail.html" class="product__images">
                    <img src="{{ asset('assets/img/BISCUITS-3.png') }}" alt="Biscuit-3" class="product__img default">

                    <img src="{{ asset('assets/img/BISCUITS-3.png') }}" alt="Biscuit-3" class="product__img hover">
                </a>
                <div class="product__actions">
                    <a href="#" class="action__btn" aria-label="Quick View">
                        <i class='bx bx-expand-horizontal'></i>
                    </a>

                    <a href="#" class="action__btn" aria-label="Add To Wishlist">
                        <i class='bx bx-heart'></i>
                    </a>

                    <a href="#" class="action__btn" aria-label="Compare">
                        <i class='bx bx-shuffle'></i>
                    </a>
                </div>

                <div class="product__badge light-orange">Hot</div>
            </div>

            <div class="product__content">
                <span class="product__category">Biscuits</span>
                <a href="details.html">
                    <h3 class="product__title">Bread Stix - Blue</h3>
                </a>

                <div class="product__rating">
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                </div>

                <div class="product__price flex">
                    <span class="new__price">₱ 7.00</span>
                    <span class="old__price">₱ 9.00</span>
                </div>

                <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                    <i class='bx bx-cart-alt'></i>
                </a>
            </div>
        </div>

        <div class="product__item">
            <div class="product__banner">
                <a href="detail.html" class="product__images">
                    <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Biscuit-1" class="product__img default">

                    <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Biscuit-1" class="product__img hover">
                </a>

                <div class="product__actions">
                    <a href="#" class="action__btn" aria-label="Quick View">
                        <i class='bx bx-expand-horizontal'></i>
                    </a>
                    <a href="#" class="action__btn" aria-label="Add To Wishlist">
                        <i class='bx bx-heart'></i>
                    </a>
                    <a href="#" class="action__btn" aria-label="Compare">
                        <i class='bx bx-shuffle'></i>
                    </a>
                </div>

                <div class="product__badge light-pink">Hot</div>
            </div>

            <div class="product__content">
                <span class="product__category">Biscuits</span>
                <a href="details.html">
                    <h3 class="product__title">Bread Stix - Blue</h3>
                </a>
                <div class="product__rating">
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                </div>
                <div class="product__price flex">
                    <span class="new__price">₱ 7.00</span>
                    <span class="old__price">₱ 9.00</span>
                </div>
                <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                    <i class='bx bx-cart-alt'></i>
                </a>
            </div>
        </div>

        <div class="product__item">
            <div class="product__banner">
                <a href="detail.html" class="product__images">
                    <img src="{{ asset('assets/img/BISCUITS-2.png') }}" alt="Biscuit-2" class="product__img default">

                    <img src="{{ asset('assets/img/BISCUITS-2.png') }}" alt="Biscuit-2" class="product__img hover">
                </a>

                <div class="product__actions">
                    <a href="#" class="action__btn" aria-label="Quick View">
                        <i class='bx bx-expand-horizontal'></i>
                    </a>

                    <a href="#" class="action__btn" aria-label="Add To Wishlist">
                        <i class='bx bx-heart'></i>
                    </a>

                    <a href="#" class="action__btn" aria-label="Compare">
                        <i class='bx bx-shuffle'></i>
                    </a>
                </div>

                <div class="product__badge light-green">Hot</div>
            </div>

            <div class="product__content">
                <span class="product__category">Biscuits</span>

                <a href="details.html">
                    <h3 class="product__title">Bread Stix - Blue</h3>
                </a>

                <div class="product__rating">
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                </div>

                <div class="product__price flex">
                    <span class="new__price">₱ 7.00</span>
                    <span class="old__price">₱ 9.00</span>
                </div>

                <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                    <i class='bx bx-cart-alt'></i>
                </a>
            </div>
        </div>

        <div class="product__item">
            <div class="product__banner">
                <a href="detail.html" class="product__images">
                    <img src="{{ asset('assets/img/BISCUITS-3.png') }}" alt="Biscuit-3" class="product__img default">

                    <img src="{{ asset('assets/img/BISCUITS-3.png') }}" alt="Biscuit-3" class="product__img hover">
                </a>
                <div class="product__actions">
                    <a href="#" class="action__btn" aria-label="Quick View">
                        <i class='bx bx-expand-horizontal'></i>
                    </a>

                    <a href="#" class="action__btn" aria-label="Add To Wishlist">
                        <i class='bx bx-heart'></i>
                    </a>

                    <a href="#" class="action__btn" aria-label="Compare">
                        <i class='bx bx-shuffle'></i>
                    </a>
                </div>

                <div class="product__badge light-orange">Hot</div>
            </div>

            <div class="product__content">
                <span class="product__category">Biscuits</span>
                <a href="details.html">
                    <h3 class="product__title">Bread Stix - Blue</h3>
                </a>

                <div class="product__rating">
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                </div>

                <div class="product__price flex">
                    <span class="new__price">₱ 7.00</span>
                    <span class="old__price">₱ 9.00</span>
                </div>

                <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                    <i class='bx bx-cart-alt'></i>
                </a>
            </div>
        </div>

        <div class="product__item">
            <div class="product__banner">
                <a href="detail.html" class="product__images">
                    <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Biscuit-1" class="product__img default">

                    <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Biscuit-1" class="product__img hover">
                </a>

                <div class="product__actions">
                    <a href="#" class="action__btn" aria-label="Quick View">
                        <i class='bx bx-expand-horizontal'></i>
                    </a>
                    <a href="#" class="action__btn" aria-label="Add To Wishlist">
                        <i class='bx bx-heart'></i>
                    </a>
                    <a href="#" class="action__btn" aria-label="Compare">
                        <i class='bx bx-shuffle'></i>
                    </a>
                </div>

                <div class="product__badge light-pink">Hot</div>
            </div>

            <div class="product__content">
                <span class="product__category">Biscuits</span>
                <a href="details.html">
                    <h3 class="product__title">Bread Stix - Blue</h3>
                </a>
                <div class="product__rating">
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                </div>
                <div class="product__price flex">
                    <span class="new__price">₱ 7.00</span>
                    <span class="old__price">₱ 9.00</span>
                </div>
                <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                    <i class='bx bx-cart-alt'></i>
                </a>
            </div>
        </div>

        <div class="product__item">
            <div class="product__banner">
                <a href="detail.html" class="product__images">
                    <img src="{{ asset('assets/img/BISCUITS-2.png') }}" alt="Biscuit-2" class="product__img default">

                    <img src="{{ asset('assets/img/BISCUITS-2.png') }}" alt="Biscuit-2" class="product__img hover">
                </a>

                <div class="product__actions">
                    <a href="#" class="action__btn" aria-label="Quick View">
                        <i class='bx bx-expand-horizontal'></i>
                    </a>

                    <a href="#" class="action__btn" aria-label="Add To Wishlist">
                        <i class='bx bx-heart'></i>
                    </a>

                    <a href="#" class="action__btn" aria-label="Compare">
                        <i class='bx bx-shuffle'></i>
                    </a>
                </div>

                <div class="product__badge light-green">Hot</div>
            </div>

            <div class="product__content">
                <span class="product__category">Biscuits</span>

                <a href="details.html">
                    <h3 class="product__title">Bread Stix - Blue</h3>
                </a>

                <div class="product__rating">
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                </div>

                <div class="product__price flex">
                    <span class="new__price">₱ 7.00</span>
                    <span class="old__price">₱ 9.00</span>
                </div>

                <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                    <i class='bx bx-cart-alt'></i>
                </a>
            </div>
        </div>

        <div class="product__item">
            <div class="product__banner">
                <a href="detail.html" class="product__images">
                    <img src="{{ asset('assets/img/BISCUITS-3.png') }}" alt="Biscuit-3" class="product__img default">

                    <img src="{{ asset('assets/img/BISCUITS-3.png') }}" alt="Biscuit-3" class="product__img hover">
                </a>
                <div class="product__actions">
                    <a href="#" class="action__btn" aria-label="Quick View">
                        <i class='bx bx-expand-horizontal'></i>
                    </a>

                    <a href="#" class="action__btn" aria-label="Add To Wishlist">
                        <i class='bx bx-heart'></i>
                    </a>

                    <a href="#" class="action__btn" aria-label="Compare">
                        <i class='bx bx-shuffle'></i>
                    </a>
                </div>

                <div class="product__badge light-orange">Hot</div>
            </div>

            <div class="product__content">
                <span class="product__category">Biscuits</span>
                <a href="details.html">
                    <h3 class="product__title">Bread Stix - Blue</h3>
                </a>

                <div class="product__rating">
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                </div>

                <div class="product__price flex">
                    <span class="new__price">₱ 7.00</span>
                    <span class="old__price">₱ 9.00</span>
                </div>

                <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                    <i class='bx bx-cart-alt'></i>
                </a>
            </div>
            
        </div>

        <div class="product__item">
            <div class="product__banner">
                <a href="detail.html" class="product__images">
                    <img src="{{ asset('assets/img/BISCUITS-3.png') }}" alt="Biscuit-3" class="product__img default">

                    <img src="{{ asset('assets/img/BISCUITS-3.png') }}" alt="Biscuit-3" class="product__img hover">
                </a>
                <div class="product__actions">
                    <a href="#" class="action__btn" aria-label="Quick View">
                        <i class='bx bx-expand-horizontal'></i>
                    </a>

                    <a href="#" class="action__btn" aria-label="Add To Wishlist">
                        <i class='bx bx-heart'></i>
                    </a>

                    <a href="#" class="action__btn" aria-label="Compare">
                        <i class='bx bx-shuffle'></i>
                    </a>
                </div>

                <div class="product__badge light-orange">Hot</div>
            </div>

            <div class="product__content">
                <span class="product__category">Biscuits</span>
                <a href="details.html">
                    <h3 class="product__title">Bread Stix - Blue</h3>
                </a>

                <div class="product__rating">
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                    <i class='bx bx-star'></i>
                </div>

                <div class="product__price flex">
                    <span class="new__price">₱ 7.00</span>
                    <span class="old__price">₱ 9.00</span>
                </div>

                <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                    <i class='bx bx-cart-alt'></i>
                </a>
            </div>
        </div>
    </div>

    <ul class="pagination">
        <li>
            <a href="#" class="pagination__link active">01</a>
        </li>

        <li>
            <a href="#" class="pagination__link">02</a>
        </li>

        <li>
            <a href="#" class="pagination__link">03</a>
        </li>

        <li>
            <a href="#" class="pagination__link">...</a>
        </li>

        <li>
            <a href="#" class="pagination__link">10</a>
        </li>

        <li>
            <a href="#" class="pagination__link icon">
                <i class="ri-arrow-right-s-line"></i>
            </a>
        </li>
    </ul>
</section>

@include('home.footer')