@foreach($products as $product)
        <div class="product__item
            {{ $product->product_stocks == 0 ? 'out-of-stock' : '' }}
            {{ $product->product_stocks > 0 && $product->product_stocks < 10 ? 'low-stock' : '' }}">

            <div class="product__banner">
                <a href="{{ route('home.details', $product->id) }}" class="product__images">
                    <img src="{{ asset('assets/img/' . $product->product_image) }}" alt="{{ $product->product_name }}" class="product__img default">
                    <img src="{{ asset('assets/img/' . $product->product_image) }}" alt="{{ $product->product_name }}" class="product__img hover">
                </a>
                @if ($product->product_stocks == 0)
                    <div class="out-of-stock-message">Out of Stock</div>
                @elseif ($product->product_stocks > 0 && $product->product_stocks < 10)
                    <div class="low-stock-message">Low Stock</div>
                @endif
            </div>
            <div class="product__content">
                <span class="product__category">{{ $product->category_name }}</span>
                <a href="{{ route('home.details', $product->id) }}">
                    <h3 class="product__title">{{ $product->product_name }}</h3>
                </a>
                <div class="product__price">
                    <span class="new__price">₱ {{ number_format($product->product_price, 2) }}</span>
                </div>
                <form id="addToCartForm-{{ $product->id }}" class="add-to-cart-form" data-url="/user/cart/add">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <button type="button" class="action__btn cart__btn {{ $product->product_stocks == 0 ? 'disabled' : '' }}">
                        <i class="bx bx-cart-alt"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach