<main class="main">
    <!--==================== HOME ====================-->
    <section class="home" id="home">
        <div class="home__container container grid">
            <img src="{{ asset('assets/img/home.png') }}" alt="" class="home__img">

            <div class="home__data">
                <h1 class="home__title">
                    SHOP WITH NO <br> LIMITS
                </h1>

                <p class="home__description">
                    We got everything for your schooling needs!
                    Our extensive collection ensures you're well-prepared and set for success.
                </p>

                <a href="#products" class="button button--flex">
                    Shop Now <i class="ri-arrow-right-down-line button__icon"></i>
                </a>
            </div>

            <div class="home__social">
                <span class="home__social-follow">Follow Us</span>

                <div class="home__social-links">
                    <a href="https://www.facebook.com/" target="_blank" class="home__social-link">
                        <i class="ri-facebook-fill"></i>
                    </a>
                    <a href="https://www.instagram.com/" target="_blank" class="home__social-link">
                        <i class="ri-instagram-line"></i>
                    </a>
                    <a href="https://twitter.com/" target="_blank" class="home__social-link">
                        <i class="ri-twitter-fill"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!--==================== ABOUT ====================-->
    <section class="about section container" id="about">
        <div class="about__container grid">
            <div class="about__images">
                <img src="{{ asset('assets/img/MAYAH.jpg') }}" alt="" class="about__img">
            </div>
            
            <div class="about__data">
                <h2 class="section__title about__title">
                    Who we really are & <br> why choose us
                </h2>

                <p class="about__description">
                    At Mayah Store, we understand that preparation is the key to success. <br><br>

                    That's why we offer a comprehensive selection of products tailored to meet all your academic and personal needs.
                    From basic school supplies like notebooks and pens to advanced tech gadgets that keep you ahead of the curve,
                    our inventory is carefully curated to enhance your educational experience. <br><br>

                    Dive into our extensive collection of high-quality items, including eco-friendly stationery,
                    innovative study aids, and the latest electronic devices,
                    all designed to support and inspire your journey towards academic excellence and personal growth.
                    Whether you're gearing up for a new school year or tackling everyday challenges,
                    Mayah Store is your trusted partner in achieving success and exceeding your goals.
                </p>

                <div class="about__details">
                    <p class="about__details-description">
                        <i class="ri-checkbox-fill about__details-icon"></i>
                        We ensure your orders are processed promptly.
                    </p>
                    <p class="about__details-description">
                        <i class="ri-checkbox-fill about__details-icon"></i>
                        We provide guides to help you make the best use of your products.
                    </p>
                    <p class="about__details-description">
                        <i class="ri-checkbox-fill about__details-icon"></i>
                        We're always available for support after your purchase.
                    </p>
                    <p class="about__details-description">
                        <i class="ri-checkbox-fill about__details-icon"></i>
                        100% satisfaction guaranteed.
                    </p>
                </div>

                <a href="#products" class="button--link button--flex">
                    Shop Now <i class="ri-arrow-right-down-line button__icon"></i>
                </a>
            </div>
        </div>
    </section>

    <!--==================== STEPS ====================-->
    <section class="steps section container">
        <div class="steps__bg">
            <h2 class="section__title-center steps__title">
                Steps to Start Shopping with <br> Mayah Store
            </h2>

            <div class="steps__container grid">
                <div class="steps__card">
                    <div class="steps__card-number">01</div>
                    <h3 class="steps__card-title">Browse Our Products</h3>
                    <p class="steps__card-description">
                        Explore a wide selection of digital and physical products tailored to your needs. Simply browse through our categories and select the items that interest you.
                    </p>
                </div>

                <div class="steps__card">
                    <div class="steps__card-number">02</div>
                    <h3 class="steps__card-title">Add to Cart</h3>
                    <p class="steps__card-description">
                        Once you find the products you love, add them to your shopping cart. You can review your selections before proceeding to checkout.
                    </p>
                </div>

                <div class="steps__card">
                    <div class="steps__card-number">03</div>
                    <h3 class="steps__card-title">Checkout and Enjoy</h3>
                    <p class="steps__card-description">
                        Complete your order by providing the necessary details. Your order will be confirmed and prepared in advance, ensuring a smooth and timely process for collection or future arrangements.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!--==================== PRODUCTS ====================-->
    <section class="products section container" id="products">
        <div class="products__container container">
            <h2 class="section__title-center">
                Choose our delicious and best products
            </h2>

            <p class="product__description">
                Here are some selected products from our showroom, all are in excellent 
                shape and has a long life span. Buy and enjoy best quality.
            </p>

            <!-- products -->
        
            @if(isset($error) && $error)
            <div class="searchResult">
                <h3>
                    -> Search Result
                </h3>
            </div>

            <div class="products__content grid">
                <p>
                    {{ $error }}
                </p>
            </div>

            @elseif(isset($results) && count($results) > 0)
            <div class="searchResult">
                <h3>
                    -> Search Result
                </h3>
            </div>

            <div class="products__content grid">
                @foreach ($results as $result)
                    <article class="products__card {{ $result->category->slug }} all">
                        <div class="products__shape">
                            <img src="{{ asset('assets/img/' . $result->product_image) }}" alt="" class="products__img">
                        </div>

                        <div class="products__data">
                            <h2 class="products__price">
                                {{ $result->product_price }} Pesos
                            </h2>

                            <h3 class="products__name">
                                {{ $result->product_name }}
                            </h3>

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
                    <h3 class="products__title">
                        {{ $category->category_name }}
                    </h3>

                    <span class="products__stock">
                        {{ $category->products_count }} Products
                    </span>
                </li>
            @endforeach
        </ul>
      
        <div class="products__content grid">
            @foreach ($products as $product)
                <article class="products__card {{ $product->category->slug }} all {{ $product->product_stocks == 0 ? 'grayed-out' : '' }}">
                    <div class="products__shape">
                        <img src="{{ asset('assets/img/' . $product->product_image) }}" alt="" class="products__img">
                    </div>

                    <div class="products__data">
                        <h2 class="products__price">
                            {{ $product->product_price }} Pesos
                        </h2>

                        <h3 class="products__name">
                            {{ $product->product_name }}
                        </h3>

                        <!-- Display out of stock or low stock warning -->
                        @if($product->product_stocks == 0)
                            <div class="out-of-stock">
                                Out of Stock
                            </div>

                        @elseif($product->product_stocks < 10)
                            <div class="low-of-stock">
                                Only {{ $product->product_stocks }} left in stock!
                            </div>

                        @endif

                        <!-- Add product to cart button -->
                        <form action="{{ route('home.inserttocart') }}" method="POST" class="add-to-cart-form">
                            @csrf
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <button type="submit" class="button products__button" {{ $product->product_stocks == 0 ? 'disabled' : '' }}>
                                <i class='bx bx-shopping-bag'></i>
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
        @endif
    </section>

    <!--==================== QUESTIONS ====================-->
    <section class="questions section" id="faqs">
        <h2 class="section__title-center questions__title container">
            Some common questions <br> were often asked
        </h2>

        <div class="questions__container container grid">
            <div class="questions__group">
                <div class="questions__item">
                    <header class="questions__header">
                        <i class="ri-add-line questions__icon"></i>
                        <h3 class="questions__item-title">
                            How do I place an order?
                        </h3>
                    </header>

                    <div class="questions__content">
                        <p class="questions__description">
                            To place an order, browse through our categories, select the items you like, add them to your cart, and proceed to checkout to complete the purchase.
                        </p>
                    </div>
                </div>

                <div class="questions__item">
                    <header class="questions__header">
                        <i class="ri-add-line questions__icon"></i>
                        <h3 class="questions__item-title">
                            Can I modify my order after placing it?
                        </h3>
                    </header>

                    <div class="questions__content">
                        <p class="questions__description">
                            Yes, you can modify your order as long as it hasn't been processed. Please contact our customer support team as soon as possible to make any changes.
                        </p>
                    </div>
                </div>

                <div class="questions__item">
                    <header class="questions__header">
                        <i class="ri-add-line questions__icon"></i>
                        <h3 class="questions__item-title">
                            What payment methods do you accept?
                        </h3>
                    </header>

                    <div class="questions__content">
                        <p class="questions__description">
                            We currently accept PayMaya and Gcash as our primary payment methods. These secure and convenient platforms allow for easy transactions.
                        </p>
                    </div>
                </div>
            </div>

            <div class="questions__group">
                <div class="questions__item">
                    <header class="questions__header">
                        <i class="ri-add-line questions__icon"></i>
                        <h3 class="questions__item-title">
                            How do I know when my order is ready for collection?
                        </h3>
                    </header>

                    <div class="questions__content">
                        <p class="questions__description">
                            Once your order is processed and ready, we will send you an email or SMS notification with details on how to collect your items.
                        </p>
                    </div>
                </div>

                <div class="questions__item">
                    <header class="questions__header">
                        <i class="ri-add-line questions__icon"></i>
                        <h3 class="questions__item-title">
                            Do you offer refunds or exchanges?
                        </h3>
                    </header>

                    <div class="questions__content">
                        <p class="questions__description">
                            Yes, we offer refunds or exchanges for items that meet our return policy criteria. Please check our refund policy page for more information.
                        </p>
                    </div>
                </div>

                <div class="questions__item">
                    <header class="questions__header">
                        <i class="ri-add-line questions__icon"></i>
                        <h3 class="questions__item-title">
                            How can I contact customer support?
                        </h3>
                    </header>

                    <div class="questions__content">
                        <p class="questions__description">
                            You can reach our customer support team through email, phone, or live chat. Visit our Contact Us page for more details on how to get in touch.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
