@forelse($products as $product)
    <div class="product__item
        {{ $product->product_stocks == 0 ? 'out-of-stock' : '' }}
        {{ $product->product_stocks > 0 && $product->product_stocks < 10 ? 'low-stock' : '' }}">

        <!-- Product Banner -->
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
            <div class="product__actions">
                <a href="{{ route('home.details', $product->id) }}" class="action__btn" aria-label="Quick View">
                    <i class='bx bx-expand-horizontal'></i>
                </a>
                <form id="wish-button-{{ $product->id }}" action="{{ route('addtowish', $product->id) }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="#" class="action__btn" aria-label="Add To Wishlist" onclick="document.getElementById('wish-button-{{ $product->id }}').submit();">
                    <i class='bx bx-heart'></i>
                </a>
            </div>
        </div>
        <div class="product__content">
            <span class="product__category">{{ $product->category_name }}</span>
            <a href="{{ route('home.details', $product->id) }}">
                <h3 class="product__title">{{ $product->product_name }}</h3>
            </a>
            <div class="product__price flex">
                <span class="new__price" style="color: {{ $product->product_stocks > 0 && $product->product_stocks < 10 ? 'red' : ($product->product_stocks == 0 ? 'black' : 'inherit') }};">
                    ₱ {{ number_format($product->product_price, 2) }}
                </span>
                @if($product->product_old_price)
                <span class="old__price">₱ {{ number_format($product->product_old_price, 2) }}</span>
                @endif
            </div>
            <form action="{{ route('home.inserttocart') }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="id" value="{{ $product->id }}">
                <button type="submit" class="action__btn cart__btn 
                        {{ $product->product_stocks == 0 ? 'disabled' : '' }} 
                        {{ $product->product_stocks > 0 && $product->product_stocks < 10 ? 'low-stock-btn' : '' }}"
                    aria-label="Add To Cart"
                    aria-disabled="{{ $product->product_stocks == 0 ? 'true' : 'false' }}"
                    {{ $product->product_stocks == 0 ? 'disabled' : '' }}>
                    <i class='bx bx-cart-alt'></i>
                </button>
            </form>
        </div>
    </div>
    @empty
    <p>No products found for the selected categories.</p>
    @endforelse