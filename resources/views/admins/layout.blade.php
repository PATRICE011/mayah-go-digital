<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--=============== FAVICON ===============-->
    <link rel="shortcut icon" href="{{ asset('assets/img/MAYAH-STORE-LOGO.jpg') }}" type="image/x-icon">

    <!--=============== BOXICONS ===============-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!--=============== REMIXICONS ===============-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">

    <!-- bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Bootstrap CSS (if not already included) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">

     <!-- ====== toastr ========-->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <title>@yield('title', 'Mayah Store Official - ADMIN')</title>
    @yield('styles')
</head>

<body>
    <!--=============== NAV ===============-->
    <div class="nav" id="nav">
        <nav class="nav__content">
            <div class="nav__toggle" id="nav-toggle">
                <i class='bx bx-chevron-right'></i>
            </div>

            <a href="#" class="nav__logo">
                <img src="{{ asset('assets/img/MAYAH-STORE-LOGO.jpg') }}" alt="" class="nav__logo-img">
                <span class="nav__logo-name">Mayah Store</span>
            </a>

            <div class="nav__list">
                <a href="{{ route('admins.dashboard') }}" class="nav__link active-link">
                    <i class='bx bx-grid-alt'></i>
                    <span class="nav__name">Dashboard</span>
                </a>
                
                <a href="{{ route('admins.orders') }}" class="nav__link">
                    <i class='bx bx-cart-alt'></i>
                    <span class="nav__name">Manage Orders</span>
                </a>

                <!-- <a href="{{ route('admins.pos') }}" class="nav__link">
                    <i class='bx bx-envelope'></i>
                    <span class="nav__name">POS</span>
                </a>

                <a href="{{ route('admins.posOrders') }}" class="nav__link">
                    <i class='bx bx-envelope'></i>
                    <span class="nav__name">POS Orders</span>
                </a> -->

                <a href="{{ route('admins.category') }}" class="nav__link">
                <i class='bx bx-purchase-tag-alt' ></i>
                    <span class="nav__name">Manage Category</span>
                </a>

                <a href="{{ route('admins.inventory') }}" class="nav__link">
                    <i class='bx bx-box'></i>
                    <span class="nav__name">Manage Inventory</span>
                </a>

                <a href="#" class="nav__link">
                    <i class='bx bx-cog'></i>
                    <span class="nav__name">Settings</span>
                </a>

                <form action="{{ route('admins.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav__link">
                        <i class='bx bx-log-out-circle' ></i>
                        <span class="nav__name">Logout</span>
                    </button>
                </form>
            </div>
        </nav>
    </div>
    
    @yield('content')
    
    <script src="{{ asset('assets/js/admin.js') }}"></script>

    <!-- bootstrap -->
    <!-- Bootstrap Bundle with Popper.js (this handles dropdowns) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
</body>

</html>
