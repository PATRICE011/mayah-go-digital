@extends('admins.layout')
@section('title', 'Mayah Store - Admin POS Orders')
@section('content')
@include('admins.adminheader', ['activePage' => 'pos'])

<div class="dashboard-wrapper">
    <div class="container-fluid  dashboard-content">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h3 class="mb-2">POS Orders</h3>

                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#" class="breadcrumb-link">Dashboard</a>
                                </li>

                                <li class="breadcrumb-item">
                                    <a href="#" class="breadcrumb-link">POS Orders</a>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar Categories -->
            <div class="col-md-3 bg-light border-end pb-3">
                <h5 class="p-3">Categories</h5>
                <ul class="list-group">
                    <li class="list-group-item">Breakfast</li>
                    <li class="list-group-item">Rice & Curry</li>
                    <li class="list-group-item">Fried Rice</li>
                    <li class="list-group-item">Noodles</li>
                    <li class="list-group-item">Koththu</li>
                    <li class="list-group-item">Biriyani</li>
                    <li class="list-group-item">Side Dishes</li>
                    <li class="list-group-item">Beverages</li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-6">
                <h5 class="p-3">Products</h5>
                <div class="row row-cols-2 row-cols-md-3 g-3">
                    <div class="col">
                        <div class="card">
                            <img src="https://via.placeholder.com/150" class="card-img-top" alt="Product">
                            <div class="card-body">
                                <h6 class="card-title">Egg Biriyani</h6>
                                <p class="card-text">Rs. 650</p>
                                <button class="btn btn-primary btn-sm w-100">Add</button>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <img src="https://via.placeholder.com/150" class="card-img-top" alt="Product">
                            <div class="card-body">
                                <h6 class="card-title">Beef Biriyani</h6>
                                <p class="card-text">Rs. 950</p>
                                <button class="btn btn-primary btn-sm w-100">Add</button>
                            </div>
                        </div>
                    </div>
                    <!-- Add more product cards here -->
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation example" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Checkout Section -->
            <div class="col-md-3 bg-light border-start">
                <h5 class="p-3">Checkout</h5>
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Fish Noodles
                        <span>Rs. 650</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Beef Biriyani
                        <span>Rs. 950</span>
                    </li>
                    <!-- Add more items dynamically here -->
                </ul>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Total:</span>
                        <span>Rs. 1600</span>
                    </div>
                </div>
                <button class="btn btn-success w-100 mb-2">Checkout</button>
                <button class="btn btn-secondary w-100">Clear</button>
            </div>
        </div>
    </div>
</div>
@endsection