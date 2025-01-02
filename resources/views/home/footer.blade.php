<!--==================== FOOTER ====================-->
<section class="footer container">
    <div class="footer__container grid">
        <div class="footer__content">
            <a href="{{url('/user')}}" class="footer__logo">
                <i class="ri-restaurant-2-fill footer__logo-icon"></i> Mayah Store
            </a>

            <h4 class="footer__subtitle">Contact</h4>

            <p class="footer__description">
                <span>Address:</span> Valenzuela, Philippines
            </p>

            <p class="footer__description">
                <span>Phone:</span> +63 9999 888 777
            </p>

            <p class="footer__description">
                <span>Hours:</span> 7:00am - 7:00pm
            </p>
        </div>

        <div class="footer__content">
            <h3 class="footer__title">
                Address
            </h3>
            
            <ul class="footer__links">
                <li>
                    <a href="{{url('/about')}}" class="footer__link">About Us</a>
                </li>

                <li>
                    <a href="" class="footer__link">Pickup Information</a>
                </li>

                <li>
                    <a href="{{url('/privacypolicy')}}" class="footer__link">Privacy Policy</a>
                </li>

                <li>
                    <a href="" class="footer__link">Terms and Conditions</a>
                </li>

                <li>
                    <a href="" class="footer__link">Contact Us</a>
                </li>
            </ul>
        </div>

        <div class="footer__content">
            <h3 class="footer__title">
                My Account
            </h3>
            
            <ul class="footer__links">
                <li>
                    <a href="{{url('user/login')}}" class="footer__link">Sign In</a>
                </li>

                <li>
                    <a href="{{ url('/cart') }}" class="footer__link">View Cart</a>
                </li>

                <li>
                    <a href="{{ url('/wishlist') }}" class="footer__link">My Wishlist</a>
                </li>

                <li>
                    <a href="" class="footer__link">Help</a>
                </li>

                <li>
                    <a href="{{ url('/shop') }}" class="footer__link">Order</a>
                </li>
            </ul>
        </div>

        <div class="footer__content">
            <h3 class="footer__title">
                Payment Gateways
            </h3>

            <img src="{{ asset('assets/img/GCASH-LOGO.png')}}" alt="Gcash" class="payment__img">
            <img src="{{ asset('assets/img/MAYA-LOGO.png')}}" alt="Gcash" class="payment__img">
        </div>
    </div>

    <div class="footer__bottom">
        <p class="copyright">&copy; MAYAH STORE. All rights reserved</p>
    </div>
</section>