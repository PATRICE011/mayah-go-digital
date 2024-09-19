<div class="cart" id="cart">
    <i class='bx bx-x cart__close' id="cart-close"></i>
    
    <h2 class="cart__title-center">My Cart</h2>

    @if($cartItems && count($cartItems) > 0)
        <div class="cart__container">
            @foreach ($cartItems as $item)
            <article class="cart__card">
                <div class="cart__box">
                    <img src="{{ asset('assets/img/' . $item->product->product_image) }}" alt="" class="cart__img">
                </div>

                <div class="cart__details">
                    <h3 class="cart__title">{{ $item->product->product_name }}</h3>
                    <span class="cart__price" id="price-{{ $item->id }}">₱ {{ $item->product->product_price }}</span>

                    <div class="cart__amount">
                        <div class="cart__amount-content">
                            <span class="cart__amount-box decrease" data-id="{{ $item->id }}">
                                <i class='bx bx-minus'></i>
                            </span>

                            <span class="cart__amount-number" id="quantity-{{ $item->id }}">{{ $item->quantity }}</span>

                            <span class="cart__amount-box increase" data-id="{{ $item->id }}">
                                <i class='bx bx-plus'></i>
                            </span>
                        </div>

                        <form action="{{ route('cartDestroy', $item->id) }}" method="POST" class="cart__delete-form">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="quantity" id="input-quantity-{{ $item->id }}" value="{{ $item->quantity }}">
                                <button type="submit" class="cart__amount-trash">
                                    <i class='bx bx-trash-alt'></i>
                                </button>
                        </form>
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        <div class="cart__prices">
            <span class="cart__prices-item">{{ count($cartItems) }} items</span>
            <span class="cart__prices-total">₱ {{ $cartItems->sum(fn($item) => $item->product->product_price * $item->quantity) }}</span>
        </div>

        <button class="cart__checkout-button">
            <a href="{{ route('cart.pay', ['cartId' => $cartId]) }}">
                Checkout
            </a>
        </button>
      
    @else
        <p>Your cart is empty.</p>
    @endif
</div>
