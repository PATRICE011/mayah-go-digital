<!--==================== MAIN ====================-->
<main class="main">
    <!--==================== HOME ====================-->
    <section class="home section--lg">
        <div class="home__container container grid">
            <div class="home__content">
                <h1 class="home__title">
                    Shop With <span>No Limits</span>
                </h1>

                <p class="home__description">
                    We got everything for your schooling needs!
                    From essential supplies to the latest gadgets,
                    our extensive collection ensures you're well-prepared and set for success.
                </p>

                <a href="{{url('/shop')}}" class="btn">
                    Shop Now
                </a>
            </div>

            <img src="{{ asset('assets/img/home.png') }}" alt="" class="home__img">
        </div>
    </section>

    <!--==================== CATEGORIES ====================-->
    <section class="categories container section">
        <h3 class="section__title"><span>Popular</span> Categories</h3>

        <div class="categories__container swiper">
            <div class="swiper-wrapper">
                @foreach ($categories as $category)
                <a href="{{ url('/shop?category=' . $category->slug) }}" class="category__item swiper-slide">
                    <img src="{{ asset('assets/img/' . $category->category_image) }}" alt="{{ $category->category_name }}">
                    <h3 class="category__title">{{ $category->category_name }}</h3>
                </a>

                @endforeach
            </div>

            <!-- Navigation Buttons -->
            <div class="swiper-button-next">
                <i class="ri-arrow-right-s-line"></i>
            </div>

            <div class="swiper-button-prev">
                <i class="ri-arrow-left-s-line"></i>
            </div>
        </div>
    </section>

    <!--==================== PRODUCTS ====================-->
    <section class="products section container">
        <div class="tab__btns">
            <div class="tab__btn active-tab" data-target="#featured">Featured</div>
            <div class="tab__btn" data-target="#popular">Popular</div>
            <div class="tab__btn" data-target="#new-added">Newly Added</div>
        </div>

        <div class="tab__items">
            @php
            // Fetch products with the highest total sales value
            $highestTotalSalesProducts = DB::table('products')
            ->join('order_items', 'products.id', '=', 'order_items.product_id') // Join order_items to calculate sales
            ->join('categories', 'products.category_id', '=', 'categories.id') // Join categories for product categories
            ->select(
            'products.id',
            'products.product_name',
            'products.product_image',
            'products.product_price',
            'products.product_stocks',
            'categories.category_name',
            DB::raw('SUM(order_items.quantity * order_items.price) as total_sales_value') // Calculate total sales value
            )
            ->where('products.product_stocks', '>', 0) // Exclude products with zero stock
            ->groupBy(
            'products.id',
            'products.product_name',
            'products.product_image',
            'products.product_price',
            'products.product_stocks',
            'categories.category_name'
            )
            ->orderBy('total_sales_value', 'DESC') // Sort by highest total sales value
            ->limit(10) // Limit to top 10 products
            ->get();
            @endphp
            <!-- ========= fetch products with the highest total sales value ========= -->
            <div class="tab__item active-tab" content id="featured">
                <div class="products__container grid">
                    @foreach ($highestTotalSalesProducts as $product)
                    <div class="product__item">
                        <!-- Product Banner -->
                        <div class="product__banner">
                            <a href="{{ url('/details', $product->id) }}" class="product__images">
                                <img src="{{ asset('assets/img/' . $product->product_image) }}"
                                    alt="{{ $product->product_name }}" class="product__img default">
                                <img src="{{ asset('assets/img/' . $product->product_image) }}"
                                    alt="{{ $product->product_name }}" class="product__img hover">
                            </a>

                            <!-- Product Actions -->
                            <div class="product__actions">
                                <a href="{{ url('/details', $product->id) }}" class="action__btn" aria-label="Quick View">
                                    <i class='bx bx-expand-horizontal'></i>
                                </a>

                                <!-- Add to Wishlist -->
                                <form
                                    id="wish-button-{{ $product->id }}"
                                    action="{{ route('addtowish', $product->id) }}"
                                    method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>

                                @auth
                                <a href="javascript:void(0);"
                                    class="action__btn"
                                    aria-label="Add To Wishlist"
                                    onclick="addToWishlist({{ $product->id }});">
                                    <i class="bx bx-heart"></i>
                                </a>
                                @else
                                <a href="{{ route('login') }}" class="action__btn" aria-label="Add To Wishlist">
                                    <i class='bx bx-heart'></i>
                                </a>
                                @endauth
                            </div>
                        </div>

                        <!-- Product Content -->
                        <div class="product__content">
                            <!-- Category Name -->
                            <span class="product__category">{{ $product->category_name }}</span>

                            <!-- Product Title -->
                            <a href="{{ url('/details', $product->id) }}">
                                <h3 class="product__title">{{ $product->product_name }}</h3>
                            </a>

                            <!-- Product Price -->
                            <div class="product__price flex">
                                <span class="new__price">₱ {{ number_format($product->product_price, 2) }}</span>
                            </div>

                            <!-- Add to Cart -->
                            @auth
                            <form
                                id="add-to-cart-form-{{ $product->id }}"
                                action="{{ route('home.inserttocart') }}"
                                method="POST"
                                class="d-inline">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">

                                <button
                                    type="submit"
                                    class="action__btn cart__btn"
                                    {{ $product->product_stocks == 0 ? 'disabled' : '' }}
                                    aria-disabled="{{ $product->product_stocks == 0 ? 'true' : 'false' }}">
                                    <i class="bx bx-cart-alt"></i>
                                </button>
                            </form>
                            @else
                            <a href="{{ route('login') }}" class="action__btn cart__btn" aria-label="Add to Cart">
                                <i class="bx bx-cart-alt"></i>
                            </a>
                            @endauth
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>


            @php
            // Fetch products sorted by most sales
            $popularProducts = DB::table('products')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
            'products.id',
            'products.product_name',
            'products.product_image',
            'products.product_price',
            'products.product_stocks',
            'categories.category_name',
            DB::raw('SUM(order_items.quantity) as total_sales') // Calculate total sales for each product
            )
            ->where('products.product_stocks', '>', 0) // Exclude products with zero stock
            ->groupBy(
            'products.id',
            'products.product_name',
            'products.product_image',
            'products.product_price',
            'products.product_stocks',
            'categories.category_name'
            )
            ->orderBy('total_sales', 'DESC') // Sort by most sales
            ->limit(10) // Limit to top 10 popular products
            ->get();
            @endphp

            <div class="tab__item" content id="popular">
                <div class="products__container grid">
                    @foreach ($popularProducts as $product)
                    <div class="product__item">
                        <!-- Product Banner -->
                        <div class="product__banner">
                            <a href="{{ url('/details', $product->id) }}" class="product__images">
                                <img src="{{ asset('assets/img/' . $product->product_image) }}"
                                    alt="{{ $product->product_name }}" class="product__img default">
                                <img src="{{ asset('assets/img/' . $product->product_image) }}"
                                    alt="{{ $product->product_name }}" class="product__img hover">
                            </a>

                            <!-- Product Actions -->
                            <div class="product__actions">
                                <a href="{{ url('/details', $product->id) }}" class="action__btn" aria-label="Quick View">
                                    <i class='bx bx-expand-horizontal'></i>
                                </a>

                                <!-- Add to Wishlist -->
                                <form
                                    id="wish-button-{{ $product->id }}"
                                    action="{{ route('addtowish', $product->id) }}"
                                    method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>

                                @auth
                                <a href="javascript:void(0);"
                                    class="action__btn"
                                    aria-label="Add To Wishlist"
                                    onclick="addToWishlist({{ $product->id }});">
                                    <i class="bx bx-heart"></i>
                                </a>
                                @else
                                <a href="{{ route('login') }}" class="action__btn" aria-label="Add To Wishlist">
                                    <i class='bx bx-heart'></i>
                                </a>
                                @endauth
                            </div>
                        </div>

                        <!-- Product Content -->
                        <div class="product__content">
                            <!-- Category Name -->
                            <span class="product__category">{{ $product->category_name }}</span>

                            <!-- Product Title -->
                            <a href="{{ url('/details', $product->id) }}">
                                <h3 class="product__title">{{ $product->product_name }}</h3>
                            </a>

                            <!-- Product Price -->
                            <div class="product__price flex">
                                <span class="new__price">₱ {{ number_format($product->product_price, 2) }}</span>
                            </div>

                            <!-- Add to Cart -->
                            @auth
                            <form
                                id="add-to-cart-form-{{ $product->id }}"
                                action="{{ route('home.inserttocart') }}"
                                method="POST"
                                class="d-inline">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">

                                <button
                                    type="submit"
                                    class="action__btn cart__btn"
                                    {{ $product->product_stocks == 0 ? 'disabled' : '' }}
                                    aria-disabled="{{ $product->product_stocks == 0 ? 'true' : 'false' }}">
                                    <i class="bx bx-cart-alt"></i>
                                </button>
                            </form>
                            @else
                            <a href="{{ route('login') }}" class="action__btn cart__btn" aria-label="Add to Cart">
                                <i class="bx bx-cart-alt"></i>
                            </a>
                            @endauth
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>


            @php
            // Fetch newly added products sorted by creation date
            $newAddedProducts = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
            'products.id',
            'products.product_name',
            'products.product_image',
            'products.product_price',
            'products.product_stocks',
            'products.created_at',
            'categories.category_name'
            )
            ->where('products.product_stocks', '>', 0) // Exclude out-of-stock products
            ->orderBy('products.created_at', 'DESC') // Sort by newly added
            ->limit(10) // Limit to the 10 most recent products
            ->get();
            @endphp

            <div class="tab__item" content id="new-added">
                <div class="products__container grid">
                    @foreach ($newAddedProducts as $product)
                    <div class="product__item">
                        <!-- Product Banner -->
                        <div class="product__banner">
                            <a href="{{ url('/details', $product->id) }}" class="product__images">
                                <img src="{{ asset('assets/img/' . $product->product_image) }}"
                                    alt="{{ $product->product_name }}" class="product__img default">
                                <img src="{{ asset('assets/img/' . $product->product_image) }}"
                                    alt="{{ $product->product_name }}" class="product__img hover">
                            </a>

                            <!-- Product Actions -->
                            <div class="product__actions">
                                <a href="{{ url('/details', $product->id) }}" class="action__btn" aria-label="Quick View">
                                    <i class='bx bx-expand-horizontal'></i>
                                </a>

                                <!-- Add to Wishlist -->
                                <form
                                    id="wish-button-{{ $product->id }}"
                                    action="{{ route('addtowish', $product->id) }}"
                                    method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>

                                @auth
                                <a href="javascript:void(0);"
                                    class="action__btn"
                                    aria-label="Add To Wishlist"
                                    onclick="addToWishlist({{ $product->id }});">
                                    <i class="bx bx-heart"></i>
                                </a>
                                @else
                                <a href="{{ route('login') }}" class="action__btn" aria-label="Add To Wishlist">
                                    <i class='bx bx-heart'></i>
                                </a>
                                @endauth
                            </div>
                        </div>

                        <!-- Product Content -->
                        <div class="product__content">
                            <!-- Category Name -->
                            <span class="product__category">{{ $product->category_name }}</span>

                            <!-- Product Title -->
                            <a href="{{ url('/details', $product->id) }}">
                                <h3 class="product__title">{{ $product->product_name }}</h3>
                            </a>

                            <!-- Product Price -->
                            <div class="product__price flex">
                                <span class="new__price">₱ {{ number_format($product->product_price, 2) }}</span>
                            </div>

                            <!-- Add to Cart -->
                            @auth
                            <form
                                id="add-to-cart-form-{{ $product->id }}"
                                action="{{ route('home.inserttocart') }}"
                                method="POST"
                                class="d-inline">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">

                                <button
                                    type="submit"
                                    class="action__btn cart__btn"
                                    {{ $product->product_stocks == 0 ? 'disabled' : '' }}
                                    aria-disabled="{{ $product->product_stocks == 0 ? 'true' : 'false' }}">
                                    <i class="bx bx-cart-alt"></i>
                                </button>
                            </form>
                            @else
                            <!-- Redirect unauthenticated users to login -->
                            <a href="{{ route('login') }}" class="action__btn cart__btn" aria-label="Add to Cart">
                                <i class="bx bx-cart-alt"></i>
                            </a>
                            @endauth
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </section>

    <!--==================== DEALS ====================-->
    <section class="deals section">
        <div class="deals__container container grid">
            <div class="deals__item">
                <div class="deals__group">
                    <h3 class="deals__brand">Deal of the Day</h3>
                    <span class="deals__category">Limited Quantities</span>
                </div>

                <h4 class="deals__title">New Flavors</h4>

                <div class="deals__price flex">
                    <span class="new__price">₱ 100.00</span>
                    <span class="old__price">₱ 120.00</span>
                </div>

                <div class="countdown">
                    <div class="countdown__amount">
                        <p class="countdown__period">02</p>
                        <span class="unit">Days</span>
                    </div>

                    <div class="countdown__amount">
                        <p class="countdown__period">22</p>
                        <span class="unit">Hours</span>
                    </div>

                    <div class="countdown__amount">
                        <p class="countdown__period">57</p>
                        <span class="unit">Mins</span>
                    </div>

                    <div class="countdown__amount">
                        <p class="countdown__period">24</p>
                        <span class="unit">Secs</span>
                    </div>
                </div>

                <div class="deals__btn">
                    <a href="details.html" class="btn btn--md">Shop Now</a>
                </div>
            </div>

            <div class="deals__item">
                <div class="deals__group">
                    <h3 class="deals__brand">Deal of the Week</h3>
                    <span class="deals__category">Limited Quantities</span>
                </div>

                <h4 class="deals__title">Try new flavors</h4>

                <div class="deals__price flex">
                    <span class="new__price">₱ 100.00</span>
                    <span class="old__price">₱ 120.00</span>
                </div>

                <div class="countdown">
                    <div class="countdown__amount">
                        <p class="countdown__period">02</p>
                        <span class="unit">Days</span>
                    </div>

                    <div class="countdown__amount">
                        <p class="countdown__period">22</p>
                        <span class="unit">Hours</span>
                    </div>

                    <div class="countdown__amount">
                        <p class="countdown__period">57</p>
                        <span class="unit">Mins</span>
                    </div>

                    <div class="countdown__amount">
                        <p class="countdown__period">24</p>
                        <span class="unit">Secs</span>
                    </div>
                </div>

                <div class="deals__btn">
                    <a href="details.html" class="btn btn--md">Shop Now</a>
                </div>
            </div>
        </div>
    </section>

    <!--==================== NEW ARRIVALS ====================-->

    <section class="new__arrivals container section">
        <h3 class="section__title"><span>New</span> Arrivals</h3>

        <div class="new__container swiper">
            <div class="swiper-wrapper">
                @php
                // Fetch products sorted by newest arrivals (based on created_at) and exclude products with zero stock
                $newArrivals = DB::table('products')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->select(
                'products.id',
                'products.product_name',
                'products.product_image',
                'products.product_price',
                'products.product_stocks',
                'products.created_at',
                'categories.category_name'
                )
                ->where('products.product_stocks', '>', 0) // Exclude products with zero stock
                ->orderBy('products.created_at', 'DESC') // Sort by newest arrivals
                ->limit(10) // Limit to 10 newest products
                ->get();
                @endphp


                @foreach ($newArrivals as $product)
                <div class="product__item swiper-slide">
                    <!-- Product Banner -->
                    <div class="product__banner">
                        <a href="{{ url('/details', $product->id) }}" class="product__images">
                            <img src="{{ asset('assets/img/' . $product->product_image) }}"
                                alt="{{ $product->product_name }}" class="product__img default">

                            <img src="{{ asset('assets/img/' . $product->product_image) }}"
                                alt="{{ $product->product_name }}" class="product__img hover">
                        </a>

                        <!-- Product Actions -->
                        <div class="product__actions">
                            <a href="{{ url('/details', $product->id) }}" class="action__btn" aria-label="Quick View">
                                <i class='bx bx-expand-horizontal'></i>
                            </a>

                            <!-- Add to Wishlist -->
                            <form
                                id="wish-button-{{ $product->id }}"
                                action="{{ route('addtowish', $product->id) }}"
                                method="POST"
                                style="display: none;">
                                @csrf
                            </form>

                            @auth
                            <a href="javascript:void(0);"
                                class="action__btn"
                                aria-label="Add To Wishlist"
                                onclick="addToWishlist({{ $product->id }});">
                                <i class="bx bx-heart"></i>
                            </a>
                            @else
                            <a href="{{ route('login') }}" class="action__btn" aria-label="Add To Wishlist">
                                <i class='bx bx-heart'></i>
                            </a>
                            @endauth
                        </div>

                    </div>

                    <!-- Product Content -->
                    <div class="product__content">
                        <!-- Product Category -->
                        <span class="product__category">{{ $product->category_name }}</span>

                        <!-- Product Title -->
                        <a href="{{ url('/details', $product->id) }}">
                            <h3 class="product__title">{{ $product->product_name }}</h3>
                        </a>

                        <!-- Product Price -->
                        <div class="product__price flex">
                            <span class="new__price">₱ {{ number_format($product->product_price, 2) }}</span>
                        </div>

                        <!-- Add to Cart Button -->
                        @auth
                        <form
                            id="add-to-cart-form-{{ $product->id }}"
                            action="{{ route('home.inserttocart') }}"
                            method="POST"
                            class="d-inline">
                            @csrf
                            <input type="hidden" name="id" value="{{ $product->id }}">

                            <button
                                type="submit"
                                class="action__btn cart__btn"
                                {{ $product->product_stocks == 0 ? 'disabled' : '' }}
                                aria-disabled="{{ $product->product_stocks == 0 ? 'true' : 'false' }}">
                                <i class="bx bx-cart-alt"></i>
                            </button>
                        </form>
                        @else
                        <!-- Redirect unauthenticated users to login -->
                        <a href="{{ route('login') }}" class="action__btn cart__btn" aria-label="Add to Cart">
                            <i class="bx bx-cart-alt"></i>
                        </a>
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>

            <div class="swiper-button-next">
                <i class="ri-arrow-right-s-line"></i>
            </div>

            <div class="swiper-button-prev">
                <i class="ri-arrow-left-s-line"></i>
            </div>
        </div>
    </section>
</main>