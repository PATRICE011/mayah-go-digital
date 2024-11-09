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
                    <div id="overview" class="overview-header content-section active">
                        <h3 class="overview__title">Overview</h3>
                        <p class="overview__description">Welcome Back, Will Smith!</p>

                        <div class="stats-container">
                            <div class="stat-box">
                                <div class="icon icon-total-orders">
                                    <i class="ri-building-fill"></i>
                                </div>

                                <h4 class="total-orders__quantity">3</h4>
                                <p class="total-orders__title">Total Orders</p>
                            </div>

                            <div class="stat-box">
                                <div class="icon icon-total-completed">
                                    <i class="ri-archive-fill"></i>    
                                </div>

                                <h4 class="total-completed__quantity">2</h4>
                                <p class="total-completed__title">Total Completed</p>
                            </div>

                            <div class="stat-box">
                                <div class="icon icon-total-returned">
                                    <i class="ri-corner-up-left-fill"></i>
                                </div>

                                <h4 class="total-returned__quantity">1</h4>
                                <p class="total-returned__title">Total Returned</p>
                            </div>

                            <div class="stat-box">
                                <div class="icon icon-wallet-balance">
                                    <i class="ri-wallet-fill"></i>
                                </div>

                                <h4 class="wallet-balance__quantity">₱0.00</h4>
                                <p class="wallet-balance__title">Wallet Balance</p>
                            </div>
                        </div>
                    </div>

                    <div id="order-history" class="content-section">
                        <h3 class="order-history__title">Order History</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Products</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>2908243</td>
                                    <td>3 Product</td>
                                    <td><span class="status pending">Pending</span></td>
                                    <td><span class="status unpaid">Unpaid</span></td>
                                    <td>₱278.00</td>
                                    <td>
                                        <!-- <li class="action-btn"> -->
                                            <a href="{{ route('home.viewmyorders') }}" class="action-btn">
                                                <i class="ri-briefcase-line"></i>
                                            </a>    
                                        <!-- </li> -->
                                    </td>
                                </tr>

                                <tr>
                                    <td>2908242</td>
                                    <td>4 Product</td>
                                    <td><span class="status delivered">Delivered</span></td>
                                    <td><span class="status paid">Paid</span></td>
                                    <td>₱720.00</td>
                                    <td>
                                        <button class="action-btn">
                                            <i class="ri-briefcase-line"></i>
                                        </button>
                                    </td>
                                </tr>

                                <tr>
                                    <td>2908241</td>
                                    <td>4 Product</td>
                                    <td><span class="status delivered">Delivered</span></td>
                                    <td><span class="status paid">Paid</span></td>
                                    <td>₱415.20</td>
                                    <td>
                                        <button class="action-btn">
                                            <i class="ri-briefcase-line"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <p>Showing 1 to 3 of 3 results</p>
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