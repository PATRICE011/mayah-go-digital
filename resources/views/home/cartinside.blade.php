<div class="cart" id="cart">
    <i class='bx bx-x cart__close' id="cart-close"></i>
    
    <h2 class="cart__title-center">My Cart</h2>

    @if($cartItems && count($cartItems) > 0)
        <div class="cart__container">
            @foreach ($cartItems as $item)
                <article class="cart__card">
                    <div class="cart__box">
                        <img src="{{ asset($item->product->product_image) }}" alt="" class="cart__img">
                    </div>

                    <div class="cart__details">
                        <h3 class="cart__title">{{ $item->product->product_name }}</h3>
                        <span class="cart__price">${{ $item->product->product_price }}</span>

                        <div class="cart__amount">
                            <div class="cart__amount-content">
                                <span class="cart__amount-box">
                                    <i class='bx bx-minus'></i>
                                </span>

                                <span class="cart__amount-number">{{ $item->quantity }}</span>

                                <span class="cart__amount-box">
                                    <i class='bx bx-plus'></i>
                                </span>
                            </div>

                            <i class='bx bx-trash-alt cart__amount-trash'></i>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="cart__prices">
            <span class="cart__prices-item">{{ count($cartItems) }} items</span>
            <span class="cart__prices-total">${{ $cartItems->sum(fn($item) => $item->product->product_price * $item->quantity) }}</span>
        </div>
    @else
        <p>Your cart is empty.</p>
    @endif
</div>
