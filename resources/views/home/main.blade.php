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

                <a href="" class="btn">
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
                <a href="shop.html" class="category__item swiper-slide">
                    <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Biscuits">

                    <h3 class="category__title">Biscuits</h3>
                </a>

                <a href="shop.html" class="category__item swiper-slide">
                    <img src="{{ asset('assets/img/DRINKS-1.png') }}" alt="Drinks">

                    <h3 class="category__title">Drinks</h3>
                </a>

                <a href="shop.html" class="category__item swiper-slide">
                    <img src="{{ asset('assets/img/SCHOOL-SUPPLIES-1.png') }}" alt="School Supplies">

                    <h3 class="category__title">School Supplies</h3>
                </a>

                <a href="shop.html" class="category__item swiper-slide">
                    <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Biscuits">

                    <h3 class="category__title">Biscuits</h3>
                </a>

                <a href="shop.html" class="category__item swiper-slide">
                    <img src="{{ asset('assets/img/DRINKS-1.png') }}" alt="Drinks">

                    <h3 class="category__title">Drinks</h3>
                </a>

                <a href="shop.html" class="category__item swiper-slide">
                    <img src="{{ asset('assets/img/SCHOOL-SUPPLIES-1.png') }}" alt="School Supplies">

                    <h3 class="category__title">School Supplies</h3>
                </a>

                <a href="shop.html" class="category__item swiper-slide">
                    <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Biscuits">

                    <h3 class="category__title">Biscuits</h3>
                </a>
            </div>

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
            <div class="tab__item active-tab" content id="featured">
                <div class="products__container grid">
                    <div class="product__item">
                        <div class="product__banner">
                            <a href="detail.html" class="product__images">
                                <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Biscuit-1" class="product__img default">

                                <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Biscuit-1" class="product__img hover">
                            </a>

                            <div class="product__actions">
                                <a href="#" class="action__btn" aria-label="Quick View">
                                    <i class='bx bx-expand-horizontal' ></i>
                                </a>

                                <a href="#" class="action__btn" aria-label="Add To Wishlist">
                                    <i class='bx bx-heart' ></i>
                                </a>

                                <a href="#" class="action__btn" aria-label="Compare">
                                    <i class='bx bx-shuffle' ></i>
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
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                            </div>

                            <div class="product__price flex">
                                <span class="new__price">₱ 7.00</span>
                                <span class="old__price">₱ 9.00</span>
                            </div>

                            <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                                <i class='bx bx-cart-alt' ></i>
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
                                    <i class='bx bx-expand-horizontal' ></i>
                                </a>

                                <a href="#" class="action__btn" aria-label="Add To Wishlist">
                                    <i class='bx bx-heart' ></i>
                                </a>

                                <a href="#" class="action__btn" aria-label="Compare">
                                    <i class='bx bx-shuffle' ></i>
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
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                            </div>

                            <div class="product__price flex">
                                <span class="new__price">₱ 7.00</span>
                                <span class="old__price">₱ 9.00</span>
                            </div>

                            <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                                <i class='bx bx-cart-alt' ></i>
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
                                    <i class='bx bx-expand-horizontal' ></i>
                                </a>

                                <a href="#" class="action__btn" aria-label="Add To Wishlist">
                                    <i class='bx bx-heart' ></i>
                                </a>

                                <a href="#" class="action__btn" aria-label="Compare">
                                    <i class='bx bx-shuffle' ></i>
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
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                            </div>

                            <div class="product__price flex">
                                <span class="new__price">₱ 7.00</span>
                                <span class="old__price">₱ 9.00</span>
                            </div>

                            <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                                <i class='bx bx-cart-alt' ></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab__item" content id="popular">
                <div class="products__container grid">
                    <div class="product__item">
                        <div class="product__banner">
                            <a href="detail.html" class="product__images">
                                <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Biscuit-1" class="product__img default">

                                <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Biscuit-1" class="product__img hover">
                            </a>

                            <div class="product__actions">
                                <a href="#" class="action__btn" aria-label="Quick View">
                                    <i class='bx bx-expand-horizontal' ></i>
                                </a>

                                <a href="#" class="action__btn" aria-label="Add To Wishlist">
                                    <i class='bx bx-heart' ></i>
                                </a>

                                <a href="#" class="action__btn" aria-label="Compare">
                                    <i class='bx bx-shuffle' ></i>
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
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                            </div>

                            <div class="product__price flex">
                                <span class="new__price">₱ 7.00</span>
                                <span class="old__price">₱ 9.00</span>
                            </div>

                            <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                                <i class='bx bx-cart-alt' ></i>
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
                                    <i class='bx bx-expand-horizontal' ></i>
                                </a>

                                <a href="#" class="action__btn" aria-label="Add To Wishlist">
                                    <i class='bx bx-heart' ></i>
                                </a>

                                <a href="#" class="action__btn" aria-label="Compare">
                                    <i class='bx bx-shuffle' ></i>
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
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                            </div>

                            <div class="product__price flex">
                                <span class="new__price">₱ 7.00</span>
                                <span class="old__price">₱ 9.00</span>
                            </div>

                            <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                                <i class='bx bx-cart-alt' ></i>
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
                                    <i class='bx bx-expand-horizontal' ></i>
                                </a>

                                <a href="#" class="action__btn" aria-label="Add To Wishlist">
                                    <i class='bx bx-heart' ></i>
                                </a>

                                <a href="#" class="action__btn" aria-label="Compare">
                                    <i class='bx bx-shuffle' ></i>
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
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                            </div>

                            <div class="product__price flex">
                                <span class="new__price">₱ 7.00</span>
                                <span class="old__price">₱ 9.00</span>
                            </div>

                            <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                                <i class='bx bx-cart-alt' ></i>
                            </a>
                        </div>
                    </div>

                    <div class="product__item">
                        <div class="product__banner">
                            <a href="detail.html" class="product__images">
                                <img src="{{ asset('assets/img/DRINKS-1.png') }}" alt="Drinks-1" class="product__img default">

                                <img src="{{ asset('assets/img/DRINKS-1.png') }}" alt="Drinks-1" class="product__img hover">
                            </a>

                            <div class="product__actions">
                                <a href="#" class="action__btn" aria-label="Quick View">
                                    <i class='bx bx-expand-horizontal' ></i>
                                </a>

                                <a href="#" class="action__btn" aria-label="Add To Wishlist">
                                    <i class='bx bx-heart' ></i>
                                </a>

                                <a href="#" class="action__btn" aria-label="Compare">
                                    <i class='bx bx-shuffle' ></i>
                                </a>
                            </div>

                            <div class="product__badge light-blue">-22%</div>
                        </div>

                        <div class="product__content">
                            <span class="product__category">Biscuits</span>
                            <a href="details.html">
                                <h3 class="product__title">Bread Stix - Blue</h3>
                            </a>

                            <div class="product__rating">
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                            </div>

                            <div class="product__price flex">
                                <span class="new__price">₱ 7.00</span>
                                <span class="old__price">₱ 9.00</span>
                            </div>

                            <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                                <i class='bx bx-cart-alt' ></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab__item" content id="new-added">
                <div class="products__container grid">
                    <div class="product__item">
                        <div class="product__banner">
                            <a href="detail.html" class="product__images">
                                <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Biscuit-1" class="product__img default">

                                <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Biscuit-1" class="product__img hover">
                            </a>

                            <div class="product__actions">
                                <a href="#" class="action__btn" aria-label="Quick View">
                                    <i class='bx bx-expand-horizontal' ></i>
                                </a>

                                <a href="#" class="action__btn" aria-label="Add To Wishlist">
                                    <i class='bx bx-heart' ></i>
                                </a>

                                <a href="#" class="action__btn" aria-label="Compare">
                                    <i class='bx bx-shuffle' ></i>
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
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                            </div>

                            <div class="product__price flex">
                                <span class="new__price">₱ 7.00</span>
                                <span class="old__price">₱ 9.00</span>
                            </div>

                            <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                                <i class='bx bx-cart-alt' ></i>
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
                                    <i class='bx bx-expand-horizontal' ></i>
                                </a>

                                <a href="#" class="action__btn" aria-label="Add To Wishlist">
                                    <i class='bx bx-heart' ></i>
                                </a>

                                <a href="#" class="action__btn" aria-label="Compare">
                                    <i class='bx bx-shuffle' ></i>
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
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                            </div>

                            <div class="product__price flex">
                                <span class="new__price">₱ 7.00</span>
                                <span class="old__price">₱ 9.00</span>
                            </div>

                            <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                                <i class='bx bx-cart-alt' ></i>
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
                                    <i class='bx bx-expand-horizontal' ></i>
                                </a>

                                <a href="#" class="action__btn" aria-label="Add To Wishlist">
                                    <i class='bx bx-heart' ></i>
                                </a>

                                <a href="#" class="action__btn" aria-label="Compare">
                                    <i class='bx bx-shuffle' ></i>
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
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                            </div>

                            <div class="product__price flex">
                                <span class="new__price">₱ 7.00</span>
                                <span class="old__price">₱ 9.00</span>
                            </div>

                            <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                                <i class='bx bx-cart-alt' ></i>
                            </a>
                        </div>
                    </div>

                    <div class="product__item">
                        <div class="product__banner">
                            <a href="detail.html" class="product__images">
                                <img src="{{ asset('assets/img/DRINKS-1.png') }}" alt="Drinks-1" class="product__img default">

                                <img src="{{ asset('assets/img/DRINKS-1.png') }}" alt="Drinks-1" class="product__img hover">
                            </a>

                            <div class="product__actions">
                                <a href="#" class="action__btn" aria-label="Quick View">
                                    <i class='bx bx-expand-horizontal' ></i>
                                </a>

                                <a href="#" class="action__btn" aria-label="Add To Wishlist">
                                    <i class='bx bx-heart' ></i>
                                </a>

                                <a href="#" class="action__btn" aria-label="Compare">
                                    <i class='bx bx-shuffle' ></i>
                                </a>
                            </div>

                            <div class="product__badge light-blue">-22%</div>
                        </div>

                        <div class="product__content">
                            <span class="product__category">Biscuits</span>
                            <a href="details.html">
                                <h3 class="product__title">Bread Stix - Blue</h3>
                            </a>

                            <div class="product__rating">
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                                <i class='bx bx-star' ></i>
                            </div>

                            <div class="product__price flex">
                                <span class="new__price">₱ 7.00</span>
                                <span class="old__price">₱ 9.00</span>
                            </div>

                            <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                                <i class='bx bx-cart-alt' ></i>
                            </a>
                        </div>
                    </div>
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
                <div class="product__item swiper-slide">
                    <div class="product__banner">
                        <a href="detail.html" class="product__images">
                            <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Biscuit-1" class="product__img default">

                            <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Biscuit-1" class="product__img hover">
                        </a>

                        <div class="product__actions">
                            <a href="#" class="action__btn" aria-label="Quick View">
                                <i class='bx bx-expand-horizontal' ></i>
                            </a>

                            <a href="#" class="action__btn" aria-label="Add To Wishlist">
                                <i class='bx bx-heart' ></i>
                            </a>

                            <a href="#" class="action__btn" aria-label="Compare">
                                <i class='bx bx-shuffle' ></i>
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
                            <i class='bx bx-star' ></i>
                            <i class='bx bx-star' ></i>
                            <i class='bx bx-star' ></i>
                            <i class='bx bx-star' ></i>
                            <i class='bx bx-star' ></i>
                        </div>

                        <div class="product__price flex">
                            <span class="new__price">₱ 7.00</span>
                            <span class="old__price">₱ 9.00</span>
                        </div>

                        <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                            <i class='bx bx-cart-alt' ></i>
                        </a>
                    </div>
                </div>

                <div class="product__item swiper-slide">
                    <div class="product__banner">
                        <a href="detail.html" class="product__images">
                            <img src="{{ asset('assets/img/BISCUITS-2.png') }}" alt="Biscuit-2" class="product__img default">

                            <img src="{{ asset('assets/img/BISCUITS-2.png') }}" alt="Biscuit-2" class="product__img hover">
                        </a>

                        <div class="product__actions">
                            <a href="#" class="action__btn" aria-label="Quick View">
                                <i class='bx bx-expand-horizontal' ></i>
                            </a>

                            <a href="#" class="action__btn" aria-label="Add To Wishlist">
                                <i class='bx bx-heart' ></i>
                            </a>

                            <a href="#" class="action__btn" aria-label="Compare">
                                <i class='bx bx-shuffle' ></i>
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
                            <i class='bx bx-star' ></i>
                            <i class='bx bx-star' ></i>
                            <i class='bx bx-star' ></i>
                            <i class='bx bx-star' ></i>
                            <i class='bx bx-star' ></i>
                        </div>

                        <div class="product__price flex">
                            <span class="new__price">₱ 7.00</span>
                            <span class="old__price">₱ 9.00</span>
                        </div>

                        <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                            <i class='bx bx-cart-alt' ></i>
                        </a>
                    </div>
                </div>

                <div class="product__item swiper-slide">
                    <div class="product__banner">
                        <a href="detail.html" class="product__images">
                            <img src="{{ asset('assets/img/BISCUITS-3.png') }}" alt="Biscuit-3" class="product__img default">

                            <img src="{{ asset('assets/img/BISCUITS-3.png') }}" alt="Biscuit-3" class="product__img hover">
                        </a>

                        <div class="product__actions">
                            <a href="#" class="action__btn" aria-label="Quick View">
                                <i class='bx bx-expand-horizontal' ></i>
                            </a>

                            <a href="#" class="action__btn" aria-label="Add To Wishlist">
                                <i class='bx bx-heart' ></i>
                            </a>

                            <a href="#" class="action__btn" aria-label="Compare">
                                <i class='bx bx-shuffle' ></i>
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
                            <i class='bx bx-star' ></i>
                            <i class='bx bx-star' ></i>
                            <i class='bx bx-star' ></i>
                            <i class='bx bx-star' ></i>
                            <i class='bx bx-star' ></i>
                        </div>

                        <div class="product__price flex">
                            <span class="new__price">₱ 7.00</span>
                            <span class="old__price">₱ 9.00</span>
                        </div>

                        <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                            <i class='bx bx-cart-alt' ></i>
                        </a>
                    </div>
                </div>

                <div class="product__item swiper-slide">
                    <div class="product__banner">
                        <a href="detail.html" class="product__images">
                            <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Biscuit-1" class="product__img default">

                            <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Biscuit-1" class="product__img hover">
                        </a>

                        <div class="product__actions">
                            <a href="#" class="action__btn" aria-label="Quick View">
                                <i class='bx bx-expand-horizontal' ></i>
                            </a>

                            <a href="#" class="action__btn" aria-label="Add To Wishlist">
                                <i class='bx bx-heart' ></i>
                            </a>

                            <a href="#" class="action__btn" aria-label="Compare">
                                <i class='bx bx-shuffle' ></i>
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
                            <i class='bx bx-star' ></i>
                            <i class='bx bx-star' ></i>
                            <i class='bx bx-star' ></i>
                            <i class='bx bx-star' ></i>
                            <i class='bx bx-star' ></i>
                        </div>

                        <div class="product__price flex">
                            <span class="new__price">₱ 7.00</span>
                            <span class="old__price">₱ 9.00</span>
                        </div>

                        <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                            <i class='bx bx-cart-alt' ></i>
                        </a>
                    </div>
                </div>

                <div class="product__item swiper-slide">
                    <div class="product__banner">
                        <a href="detail.html" class="product__images">
                            <img src="{{ asset('assets/img/BISCUITS-2.png') }}" alt="Biscuit-2" class="product__img default">

                            <img src="{{ asset('assets/img/BISCUITS-2.png') }}" alt="Biscuit-2" class="product__img hover">
                        </a>

                        <div class="product__actions">
                            <a href="#" class="action__btn" aria-label="Quick View">
                                <i class='bx bx-expand-horizontal' ></i>
                            </a>

                            <a href="#" class="action__btn" aria-label="Add To Wishlist">
                                <i class='bx bx-heart' ></i>
                            </a>

                            <a href="#" class="action__btn" aria-label="Compare">
                                <i class='bx bx-shuffle' ></i>
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
                            <i class='bx bx-star' ></i>
                            <i class='bx bx-star' ></i>
                            <i class='bx bx-star' ></i>
                            <i class='bx bx-star' ></i>
                            <i class='bx bx-star' ></i>
                        </div>

                        <div class="product__price flex">
                            <span class="new__price">₱ 7.00</span>
                            <span class="old__price">₱ 9.00</span>
                        </div>

                        <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                            <i class='bx bx-cart-alt' ></i>
                        </a>
                    </div>
                </div>

                <div class="product__item swiper-slide">
                    <div class="product__banner">
                        <a href="detail.html" class="product__images">
                            <img src="{{ asset('assets/img/BISCUITS-3.png') }}" alt="Biscuit-3" class="product__img default">

                            <img src="{{ asset('assets/img/BISCUITS-3.png') }}" alt="Biscuit-3" class="product__img hover">
                        </a>

                        <div class="product__actions">
                            <a href="#" class="action__btn" aria-label="Quick View">
                                <i class='bx bx-expand-horizontal' ></i>
                            </a>

                            <a href="#" class="action__btn" aria-label="Add To Wishlist">
                                <i class='bx bx-heart' ></i>
                            </a>

                            <a href="#" class="action__btn" aria-label="Compare">
                                <i class='bx bx-shuffle' ></i>
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
                            <i class='bx bx-star' ></i>
                            <i class='bx bx-star' ></i>
                            <i class='bx bx-star' ></i>
                            <i class='bx bx-star' ></i>
                            <i class='bx bx-star' ></i>
                        </div>

                        <div class="product__price flex">
                            <span class="new__price">₱ 7.00</span>
                            <span class="old__price">₱ 9.00</span>
                        </div>

                        <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                            <i class='bx bx-cart-alt' ></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="swiper-button-next">
                <i class="ri-arrow-right-s-line"></i>
            </div>

            <div class="swiper-button-prev">
                <i class="ri-arrow-left-s-line"></i>
            </div>
        </div>
    </section>

    <!--==================== SHOWCASE ====================-->
    <section class="showcase section">

    </section>
    
    <!--==================== QUESTIONS ====================-->
    <!-- <section class="questions section" id="faqs">
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
    </section> -->
</main>