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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
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
                            <a href="#overview" class="sidebar-link" onclick="showSection('overview', event)">
                                Overview
                            </a>
                        </li>

                        <li>
                            <a href="#order-history" class="sidebar-link" onclick="showSection('order-history', event)">
                                Order History
                            </a>
                        </li>

                        <li>
                            <a href="#" class="sidebar-link" onclick="showSection('return-orders', event)">
                                Return Orders
                            </a>
                        </li>

                        <li>
                            <a href="#account-info" class="sidebar-link" onclick="showSection('account-info', event)">
                                Account Info
                            </a>
                        </li>

                        <li>
                            <a href="#change-password" class="sidebar-link" onclick="showSection('change-password', event)">
                                Change Password
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
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                <tr>
                                    <td>{{ $order->orderDetail->order_id_custom }}</td>
                                    <td>{{ $order->orderDetail->product_count }} Product{{ $order->orderDetail->product_count > 1 ? 's' : '' }}</td>
                                    <td><span class="status {{ strtolower($order->status) }}">{{ ucfirst($order->status) }}</span></td>
                                    <td>₱{{ number_format($order->orderDetail->total_amount, 2) }}</td>
                                    <td>
                                        <a href="{{ route('home.viewmyorders', ['id' => $order->id]) }}" class="action-btn">
                                            <i class="ri-briefcase-line"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No orders found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <p>Showing {{ $orders->count() }} of {{ $orders->count() }} results</p>
                    </div>

                    <div id="account-info" class="content-section">
                        <h3 class="account-info__title">Account Info</h3>
                        <form>
                            <div class="form-group">
                                <label for="fullname">Full Name <span>*</span></label>
                                <input type="text" id="fullname" name="fullname" value="Will Smith">
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email <span>*</span></label>
                                <input type="email" id="email" name="email" value="customer@example.com">
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone <span>*</span></label>
                                <div class="phone-input">
                                    <!-- <span>BD +880 ▼</span> -->
                                    <input type="text" id="phone" name="phone" value="125333344">
                                </div>
                            </div>
                            
                            <button type="submit" class="save-button">Save Changes</button>
                        </form>
                    </div>

                    <div id="change-password" class="content-section">
                        <h3 class="change-password__title">Change Password</h3>
                        <form>
                            <div class="form-group">
                                <label for="Old Password">Old Password <span>*</span></label>
                                <input type="password" id="old-password" name="old-password">
                            </div>
                            
                            <div class="form-group">
                                <label for="New Password">New Password <span>*</span></label>
                                <input type="password" id="new-password" name="new-password">
                                <!-- <input type="email" id="email" name="email" value="customer@example.com"> -->
                            </div>
                            
                            <div class="form-group">
                                <label for="Password Confirmation">Password Confirmation <span>*</span></label>
                                <input type="password" id="password-confirmation" name="password-confirmation">
                            </div>
                            
                            <button type="submit" class="save-button">Save Changes</button>
                        </form>
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