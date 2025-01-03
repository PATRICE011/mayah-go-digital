<!--=============== HEADER ===============-->
<div class="dashboard-main-wrapper">
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
                <li class="nav-item">
                    <div id="custom-search" class="top-search-bar">
                        <input class="form-control" type="text" placeholder="Search..">
                    </div>
                </li>

                    <li class="nav-item dropdown notification">
                        <a class="nav-link nav-icons" href="#" id="navbarDropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-fw fa-bell"></i> <span class="indicator"></span></a>
                        
                        <ul class="dropdown-menu dropdown-menu-right notification-dropdown">
                            <li>
                                <div class="notification-title"> Notification</div>
                                
                                <div class="notification-list">
                                    <div class="list-group">
                                        <a href="#" class="list-group-item list-group-item-action active">
                                            <div class="notification-info">
                                                <div class="notification-list-user-block"><span class="notification-list-user-name">Jeremy Rakestraw</span>accepted your invitation to join the team.
                                                    <div class="notification-date">2 min ago</div>
                                                </div>
                                            </div>
                                        </a>

                                    <a href="#" class="list-group-item list-group-item-action">
                                        <div class="notification-info">
                                            <div class="notification-list-user-block"><span class="notification-list-user-name">John Abraham</span>is now following you
                                                <div class="notification-date">2 days ago</div>
                                            </div>
                                        </div>
                                    </a>

                                    <a href="#" class="list-group-item list-group-item-action">
                                        <div class="notification-info">
                                            <div class="notification-list-user-block"><span class="notification-list-user-name">Monaan Pechi</span> is watching your main repository
                                                <div class="notification-date">2 min ago</div>
                                            </div>
                                        </div>
                                    </a>

                                    <a href="#" class="list-group-item list-group-item-action">
                                        <div class="notification-info">
                                            <div class="notification-list-user-img"><img src="assets/images/avatar-5.jpg" alt="" class="user-avatar-md rounded-circle"></div>
                                            <div class="notification-list-user-block"><span class="notification-list-user-name">Jessica Caruso</span>accepted your invitation to join the team.
                                                <div class="notification-date">2 min ago</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>

                    <li class="nav-item dropdown nav-user">
                        <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ asset('assets/img/MAYAH-STORE-LOGO.jpg') }}" class="user-avatar-md rounded-circle">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
                            <div class="nav-user-info">
                                <h5 class="mb-0 text-white nav-user-name">John Abraham </h5>
                                <span class="status"></span><span class="ml-2">Available</span>
                            </div>
                            
                            <a class="dropdown-item" href="#"><i class="fas fa-user mr-2"></i>Account</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-cog mr-2"></i>Setting</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-power-off mr-2"></i>Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

<div class="nav-left-sidebar sidebar-dark">
    <div class="menu-list">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="d-xl-none d-lg-none" href="{{ route('admins.dashboard') }}">Dashboard</a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admins.dashboard') }}" class="nav-link {{ isset($activePage) && $activePage === 'dashboard' ? 'active' : '' }}">
                                <i class="fa fa-fw fa-user-circle"></i>Dashboard
                            </a>
                        </li>

                        <li class="nav-divider">
                            Product & Stocks
                        </li>

                    <li class="nav-item">
                        <a href="{{ route('admins.adminproducts') }}" class="nav-link {{ isset($activePage) && $activePage === 'products' ? 'active' : '' }}">
                            <i class="fas fa-fw fa-file"></i> Products
                        </a>

                            <a href="{{ route('admins.adminstocks') }}" class="nav-link {{ isset($activePage) && $activePage === 'stocks' ? 'active' : '' }}">
                                <i class="fas fa-fw fa-file"></i> Stocks
                            </a>

                            <a href="{{ route('admins.admincategories') }}" class="nav-link {{ isset($activePage) && $activePage === 'categories' ? 'active' : '' }}">
                                <i class="fas fa-fw fa-file"></i> Category
                            </a>
                        </li>
                    
                        <li class="nav-divider">
                            POS & Orders
                        </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-fw fa-file"></i> POS
                        </a>

                        <a href="{{ route('admins.adminposorders') }}" class="nav-link {{ isset($activePage) && $activePage === 'posorders' ? 'active' : '' }}">
                            <i class="fas fa-fw fa-file"></i> POS Orders
                        </a>

                        <a href="{{ route('admins.adminonlineorders') }}" class="nav-link {{ isset($activePage) && $activePage === 'onlineorders' ? 'active' : '' }}">
                            <i class="fas fa-fw fa-file"></i> Online Orders
                        </a>

                        <a href="{{ route('admins.adminrefund') }}" class="nav-link {{ isset($activePage) && $activePage === 'refund' ? 'active' : '' }}">
                            <i class="fas fa-fw fa-file"></i> Return & Refunds
                        </a>
                    </li>

                    <li class="nav-divider">
                        Users
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admins.adminadministrators') }}" class="nav-link {{ isset($activePage) && $activePage === 'administrators' ? 'active' : '' }}">
                            <i class="fas fa-fw fa-file"></i> Administrator
                        </a>

                        <a href="{{ route('admins.admincustomers') }}" class="nav-link {{ isset($activePage) && $activePage === 'customers' ? 'active' : '' }}">
                            <i class="fas fa-fw fa-file"></i> Customers
                        </a>

                        <a href="{{ route('admins.adminemployee') }}" class="nav-link {{ isset($activePage) && $activePage === 'employee' ? 'active' : '' }}">
                            <i class="fas fa-fw fa-file"></i> Employees
                        </a>
                    </li>

                    <li class="nav-divider">
                        Reports
                    </li>

                        <li class="nav-item">
                            <a href="{{ route('admins.adminaudit') }}" class="nav-link {{ isset($activePage) && $activePage === 'audit' ? 'active' : '' }}">
                                <i class="fas fa-fw fa-file"></i> Audit Trail
                            </a>
                        
                            <a href="#" class="nav-link">
                                <i class="fas fa-fw fa-file"></i> Sales Report
                            </a>

                            <a href="#" class="nav-link">
                                <i class="fas fa-fw fa-file"></i> Products Report
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>

   
</div>