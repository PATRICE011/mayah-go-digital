<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--=============== FAVICON ===============-->
    <link rel="shortcut icon" href="{{ asset('assets/img/MAYAH-STORE-LOGO.jpg') }}" type="image/x-icon">

    <!--=============== REMIXICONS ===============-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">

    <!--=============== BOXICONS ===============-->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/overview.css') }}">

    <!-- ====== Toastr ========-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <title>Document</title>
</head>
<body>
  @include('users.header')
    
    <main class="main">
        <section class="myorders" id="myorders">
            <div class="container grid blockings">
                <div class="sidebar">
                    <h2>Will Smith</h2>
                    <p>+880125333344</p>
                    <ul>
                        <li>
                            <a href="#" class="sidebar-link" onclick="showSection('overview', event)">
                                Overview
                            </a>
                        </li>
                        
                        <li>
                            <a href="#" class="sidebar-link" onclick="showSection('order-history', event)">
                                Order History
                            </a>
                        </li>

                        <li>
                            <a href="#" class="sidebar-link" onclick="showSection('return-orders', event)">
                                Return Orders
                            </a>
                        </li>

                        <li>
                            <a href="#" class="sidebar-link" onclick="showSection('account-info', event)">
                                Account Info
                            </a>
                        </li>

                        <li>
                            <a href="#" class="sidebar-link" onclick="showSection('change-password', event)">
                                Change Password
                            </a>
                        </li>

                        <li>
                            <a href="#" class="sidebar-link" onclick="showSection('address', event)">
                                Address
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="content">
                    <div class="order-details-container">
                        <h3 class="order-header">
                            <a href="#">
                                <i class="ri-arrow-left-line"></i>
                            </a>
                            Order Details
                        </h3>

                        <h1 class="order-thank-you">Thank You</h1>
                        <p class="order-status-text">Your Order status is as follows</p>
                        <p class="order-status-id">Order ID: <strong>#2908243</strong></p>

                        <div class="progress-bar">
                           <div class="progress-line completed"></div>
                           <div class="progress-line"></div>
                           <div class="progress-step active">
                               <div class="progress-icon">✓</div>
                               <div class="progress-text">Order Pending</div>
                           </div>

                           <div class="progress-step">
                               <div class="progress-icon">•</div>
                               <div class="progress-text">Order Confirmed</div>
                           </div>

                           <div class="progress-step">
                               <div class="progress-icon">•</div>
                               <div class="progress-text">Order On The Way</div>
                           </div>

                           <div class="progress-step">
                               <div class="progress-icon">•</div>
                               <div class="progress-text">Order Delivered</div>
                           </div>
                        </div>

                        <div class="order-info">
                            <!-- <p> -->
                            <p><strong>Order Date:</strong> 30.08.2024 00:47</p>
                            <p><strong>Order Type:</strong> Delivery</p>
                            <p><strong>Order Status:</strong> <span class="badge pending">Pending</span></p>
                            <p><strong>Payment Status:</strong> <span class="badge unpaid">Unpaid</span></p>
                            <p><strong>Payment Method:</strong> Cash On Delivery</p>
                        </div>

                        <div class="order-summary-header">Order Summary</div>
                            <div class="product-item">
                                <div class="product-image">
                                    <img src="air-hoodie.jpg" alt="Air Hoodie">
                                </div>

                                <div class="product-details">
                                    <div class="product-name">Air Hoodie</div>
                                    <div class="product-info">Black | S</div>
                                    <div class="product-price">$100.00</div>
                                    <div class="product-info">Quantity: 1</div>
                                </div>
                            </div>

                            <div class="product-item">
                                <div class="product-image">
                                    <img src="ultra-bounce-shoes.jpg" alt="Ultra Bounce Shoes">
                                </div>

                                <div class="product-details">
                                    <div class="product-name">Ultra Bounce Shoes</div>
                                    <div class="product-info">Black | S</div>
                                    <div class="product-price">$80.00</div>
                                    <div class="product-info">Quantity: 1</div>
                                </div>
                            </div>

                            <div class="product-item">
                                <div class="product-image">
                                    <img src="essential-hat.jpg" alt="Essential Hat">
                                </div>

                                <div class="product-details">
                                    <div class="product-name">Essential Hat</div>
                                    <div class="product-info">Black | S</div>
                                    <div class="product-price">$60.00</div>
                                    <div class="product-info">Quantity: 1</div>
                                </div>
                            </div>

                            <div class="summary-line">
                                <span>Subtotal</span>
                                <span>$240.00</span>
                            </div>

                            <div class="summary-line">
                                <span>Tax Fee</span>
                                <span>$28.00</span>
                            </div>

                            <div class="summary-line">
                                <span>Shipping Charge</span>
                                <span>$10.00</span>
                            </div>

                            <div class="summary-line">
                                <span>Discount</span>
                                <span>$0.00</span>
                            </div>

                            <div class="summary-line total">
                                <span>Total</span>
                                <span>$278.00</span>
                            </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!--=============== JavaScript for Switching Sections ===============-->
    <script>
        function showSection(sectionId) {
            // Hide all content sections
            const sections = document.querySelectorAll('.content-section');
            sections.forEach(section => section.classList.remove('active'));

            // Show the selected content section
            document.getElementById(sectionId).classList.add('active');

            // Remove active class from all sidebar links
            const links = document.querySelectorAll('.sidebar-link');
            links.forEach(link => link.classList.remove('active'));

            // Add active class to the clicked link
            event.currentTarget.classList.add('active');
        }
    </script>

    <!--=============== SCROLL REVEAL ANIMATION ===============-->
    <script src="{{ asset('assets/js/scrollreveal.min.js') }}"></script>
    
    <!--=============== MIXITUP FILTER ===============-->
    <script src="{{ asset('assets/js/mixitup.min.js') }}"></script>
    
    <!--=============== MAIN JS ===============-->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    
    <!-- Latest Bootstrap JS -->
    
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>