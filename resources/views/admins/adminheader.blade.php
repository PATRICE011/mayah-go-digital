<!--=============== HEADER ===============-->
<div class="dashboard-header">
    <nav class="navbar navbar-expand-lg bg-white fixed-top">
        <a class="navbar-brand" href="">
            MAYAH STORE
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse " id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto navbar-right-top">

                <li class="nav-item dropdown nav-user">
                    <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ asset('assets/img/MAYAH-STORE-LOGO.jpg') }}" class="user-avatar-md rounded-circle">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
                        <div class="nav-user-info">
                            <!-- Dynamic User Name -->
                            <h5 class="mb-0 text-white nav-user-name">
                                {{ Auth::user()->name ?? 'Guest' }}
                            </h5>
                            <span class="status"></span><span class="ml-2">Available</span>
                        </div>

                        <a class="dropdown-item" href="#"><i class="fas fa-user mr-2"></i>Account</a>
                        <a class="dropdown-item" href="#"><i class="fas fa-cog mr-2"></i>Setting</a>
                        <form action="{{ url('/admin/logout') }}" method="POST" id="logoutForm">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="fas fa-power-off mr-2"></i>Logout</button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>

@php
$role = Auth::user()->role_id; // Fetch user's role
@endphp

<div class="nav-left-sidebar sidebar-dark">
    <div class="menu-list">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="d-xl-none d-lg-none" href="{{ route('admins.dashboard') }}">Dashboard</a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav flex-column">
                    <!-- Dashboard - Visible for all roles -->
                    <li class="nav-item">
                        <a href="{{ route('admins.dashboard') }}" class="nav-link {{ isset($activePage) && $activePage === 'dashboard' ? 'active' : '' }}">
                            <i class="fa fa-fw fa-user-circle"></i> Dashboard
                        </a>
                    </li>

                    <!-- Product & Stocks Section -->
                    @if($role != 2) {{-- Hide for role ID 2 --}}
                    <li class="nav-divider">
                        Product & Stocks
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admins.adminproducts') }}" class="nav-link {{ isset($activePage) && $activePage === 'products' ? 'active' : '' }}">
                            <i class="fas fa-fw fa-file"></i> Products
                        </a>
                        <a href="{{ route('admins.admincategories') }}" class="nav-link {{ isset($activePage) && $activePage === 'categories' ? 'active' : '' }}">
                            <i class="fas fa-fw fa-file"></i> Category
                        </a>
                    </li>
                    @endif

                    <li class="nav-divider">
                        POS & Orders
                    </li>

                    <li class="nav-item">
                        <!-- <a href="{{ route('admins.adminposorders') }}" class="nav-link {{ isset($activePage) && $activePage === 'posorders' ? 'active' : '' }}">
                            <i class="fas fa-fw fa-file"></i> POS Orders
                        </a> -->

                        <!-- <a href="{{ route('admins.adminonlineorders') }}" class="nav-link {{ isset($activePage) && $activePage === 'onlineorders' ? 'active' : '' }}">
                            <i class="fas fa-fw fa-file"></i> Online Orders
                        </a> -->

                        <!-- <a href="{{ route('admins.adminrefund') }}" class="nav-link {{ isset($activePage) && $activePage === 'refund' ? 'active' : '' }}">
                            <i class="fas fa-fw fa-file"></i> Return & Refunds
                        </a> -->
                        @if($role == 1) {{-- Role ID 1: See all 3 pages --}}
                        <a href="{{ route('admins.adminonlineorders') }}" class="nav-link {{ isset($activePage) && $activePage === 'onlineorders' ? 'active' : '' }}">
                            <i class="fas fa-fw fa-file"></i> Online Orders
                        </a>

                        <!-- <a href="{{ route('admins.adminpos') }}" class="nav-link {{ isset($activePage) && $activePage === 'pos' ? 'active' : '' }}">
                            <i class="fas fa-fw fa-file"></i> POS
                        </a> -->

                        <!-- <a href="{{ route('admins.adminposorders') }}" class="nav-link {{ isset($activePage) && $activePage === 'posorders' ? 'active' : '' }}">
                            <i class="fas fa-fw fa-file"></i> POS Orders
                        </a> -->

                        <a href="{{ route('admins.adminrefund') }}" class="nav-link {{ isset($activePage) && $activePage === 'refund' ? 'active' : '' }}">
                            <i class="fas fa-fw fa-file"></i> Return & Refunds
                        </a>
                        @elseif($role == 2) {{-- Role ID 2: See only Online Orders --}}
                        <a href="{{ route('admins.adminonlineorders') }}" class="nav-link {{ isset($activePage) && $activePage === 'onlineorders' ? 'active' : '' }}">
                            <i class="fas fa-fw fa-file"></i> Online Orders
                        </a>
                        @endif

                    </li>

                    <!-- Users Section -->
                    @if($role != 2) {{-- Hide for role ID 2 --}}
                    <li class="nav-divider">
                        Users
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admins.admincustomers') }}" class="nav-link {{ isset($activePage) && $activePage === 'customers' ? 'active' : '' }}">
                            <i class="fas fa-fw fa-file"></i> Customers
                        </a>
                        <a href="{{ route('admins.adminemployee') }}" class="nav-link {{ isset($activePage) && $activePage === 'employee' ? 'active' : '' }}">
                            <i class="fas fa-fw fa-file"></i> Employees
                        </a>
                    </li>
                    @endif

                    <!-- Reports Section -->
                    @if($role != 2) {{-- Hide for role ID 2 --}}
                    <li class="nav-divider">
                        Reports
                    </li>
                    <li class="nav-item">
                       
                        <a href="{{url('/admin/stocks/report')}}" class="nav-link {{ isset($activePage) && $activePage === 'stocksreport' ? 'active' : 'active' }}">
                            <i class="fas fa-fw fa-file"></i> Stock In & Out Report
                        </a>

                        <a href="{{ route('admins.adminsalesreport') }}" class="nav-link {{ isset($activePage) && $activePage === 'salesreport' ? 'active' : '' }}">
                            <i class="fas fa-fw fa-file"></i> Sales Report
                        </a>

                        <a href="{{ route('admins.adminposreport') }}" class="nav-link {{ isset($activePage) && $activePage === 'posreport' ? 'active' : '' }}">
                            <i class="fas fa-fw fa-file"></i> POS Sales Report
                        </a>

                        <a href="{{ route('admins.adminproductsreport') }}" class="nav-link {{ isset($activePage) && $activePage === 'productsreport' ? 'active' : '' }}">
                            <i class="fas fa-fw fa-file"></i> Products Report
                        </a>

                        <a href="{{ route('admins.adminaudit') }}" class="nav-link {{ isset($activePage) && $activePage === 'audit' ? 'active' : '' }}">
                            <i class="fas fa-fw fa-file"></i> Audit Trail
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </nav>
    </div>
</div>