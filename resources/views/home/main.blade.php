<main class="main">
    <!--==================== HOME ====================-->
    <section class="home section" id="home">
        <div class="home__container container grid">
            <div class="home__data">
                <h1 class="home__title">SHOP <br> WITH NO <br> LIMITS</h1>

                <p class="home__description">
                    We got everything for your schooling needs!,
                    From essential supplies to the latest gadgets,
                    our extensive collection ensures you're well-prepared and set for success.
                </p>

                <div class="home__buttons">
                    <a href="#products" class="button-1 button__ghost">Buy Now</a>
                </div>
            </div>

            <div class="home__images">
                <div class="home__circle">
                    <div class="home__subcircle"></div>
                </div>

                <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="" class="home__img">
                <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="" class="home__chips-1">
                <img src="{{ asset('assets/img/BISCUITS-2.png') }}" alt="" class="home__chips-2">
                <img src="{{ asset('assets/img/BISCUITS-3.png') }}" alt="" class="home__chips-3">
                <!-- drinks -->
                <img src="{{ asset('assets/img/DRINKS-1.png') }}" alt="" class="home__tomato-1">
                <img src="{{ asset('assets/img/DRINKS-2.png') }}" alt="" class="home__tomato-2">
                <img src="{{ asset('assets/img/DRINKS-3.png') }}" alt="" class="home__leaf">
            </div>
        </div>
    </section>

    <!--==================== ABOUT ====================-->
    <section class="about section" id="about">
        <div class="about__container container grid">
            <div class="about__images">
                <img src="{{ asset('assets/img/MAYAH.jpg') }}" alt="" class="about__img">
            </div>
            
            <div class="about__data">
                <h1 class="about__title">About Us</h1>

                <p class="about__description">
                    At <span class="about__maya">Mayah Store</span>, we understand that preparation is the key to success.
                    That's why we offer a comprehensive selection of products tailored to meet all your academic and personal needs.
                    From basic school supplies like notebooks and pens to advanced tech gadgets that keep you ahead of the curve,
                    our inventory is carefully curated to enhance your educational experience.
                    Dive into our extensive collection of high-quality items, including eco-friendly stationery,
                    innovative study aids, and the latest electronic devices,
                    all designed to support and inspire your journey towards academic excellence and personal growth.
                    Whether you're gearing up for a new school year or tackling everyday challenges,
                    <span class="about__maya">Mayah Store</span> is your trusted partner in achieving success and exceeding your goals.
                </p>
            </div>
        </div>
    </section>

    <!--==================== PRODUCTS ====================-->
    
<section class="products section" id="products">
    <div class="products__container container">
        <h2 class="home__title section__title">
            Choose our delicious and best products
        </h2>

        <!-- products -->
        
    @if(isset($error) && $error)
    <div class="searchResult"><h3 >-> Search Result</h3></div>
    <div class="products__content grid">
        <p>{{ $error }}</p>
        </div>
    @elseif(isset($results) && count($results) > 0)
    <div class="searchResult"><h3 >-> Search Result</h3></div>
    <div class="products__content grid">
   
        @foreach ($results as $result)
            <article class="products__card {{ $result->category->slug }} all">
                <div class="products__shape">
                    <img src="{{ asset('assets/img/' . $result->product_image) }}" alt="" class="products__img">
                </div>
                <div class="products__data">
                    <h2 class="products__price">{{ $result->product_price }} Pesos</h2>
                    <h3 class="products__name">{{ $result->product_name }}</h3>
                    <!-- Add product to cart button -->
                    <form action="{{ route('home.inserttocart') }}" method="POST" class="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="id" value="{{ $result->id }}">
                        <button type="submit" class="button products__button">
                            <i class='bx bx-shopping-bag'></i>
                        </button>
                    </form>
                </div>
            </article>
        @endforeach
        </div>
    @else
      <!-- categories -->
      <ul class="products__filters">
            @foreach ($categories as $category)
                <li class="products__item products__line" data-filter=".{{ $category->slug }}.all">
                    <h3 class="products__title">{{ $category->category_name }}</h3>
                    <span class="products__stock">{{ $category->products_count }} Products</span>
                </li>
            @endforeach
        </ul>
    <div class="products__content grid">
        @foreach ($products as $product)
            <article class="products__card {{ $product->category->slug }} all">
                <div class="products__shape">
                    <img src="{{ asset('assets/img/' . $product->product_image) }}" alt="" class="products__img">
                </div>
                <div class="products__data">
                    <h2 class="products__price">{{ $product->product_price }} Pesos</h2>
                    <h3 class="products__name">{{ $product->product_name }}</h3>
                    <!-- Add product to cart button -->
                    <form action="{{ route('home.inserttocart') }}" method="POST" class="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="id" value="{{ $product->id }}">
                        <button type="submit" class="button products__button">
                            <i class='bx bx-shopping-bag'></i>
                        </button>
                    </form>
                </div>
            </article>
        @endforeach
        </div>
    @endif


    </div>
</section>


</main>
