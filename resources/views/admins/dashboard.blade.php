@extends('admins.layout')
@section('title', 'Mayah Store - Admin Dashboard')
@section('content')
@include('admins.adminheader', ['activePage' => 'dashboard'])

<div class="dashboard-wrapper">
    <div class="container-fluid  dashboard-content">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h3 class="mb-2">Admin Dashboard</h3>

                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Dashboard</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Customers Card -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="card fixed-card">
                    <div class="card-body">
                        <h5 class="text-muted">Customers</h5>
                        <div class="metric-value d-inline-block">
                            <h1 class="mb-1 text-primary">{{ number_format($totalCustomers) }}</h1>
                        </div>
                        <div class="metric-label d-inline-block float-right text-success">
                            <i class="fa fa-fw fa-caret-up"></i><span>5.27%</span> <!-- Placeholder for change percentage -->
                        </div>
                    </div>
                    <div id="sparkline-1"></div>
                </div>
            </div>

            <!-- Orders Card -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="card fixed-card">
                    <div class="card-body">
                        <h5 class="text-muted">Orders</h5>
                        <div class="metric-value d-inline-block">
                            <h1 class="mb-1 text-primary">{{ number_format($totalOrders) }}</h1>
                        </div>
                        <div class="metric-label d-inline-block float-right {{ $growthRate >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="fa fa-fw fa-caret-{{ $growthRate >= 0 ? 'up' : 'down' }}"></i>
                            <span>{{ number_format(abs($growthRate), 2) }}%</span>
                        </div>
                    </div>
                    <div id="sparkline-2"></div>
                </div>
            </div>

            <!-- Total Products Card -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="card fixed-card">
                    <div class="card-body">
                        <h5 class="text-muted">Total Products</h5>
                        <div class="metric-value d-inline-block">
                            <h1 class="mb-1 text-primary">{{ number_format($totalProducts) }}</h1>
                        </div>
                        <div class="metric-label d-inline-block float-right text-danger">
                            <i class="fa fa-fw fa-caret-down"></i><span>7.00%</span> <!-- Placeholder for change percentage -->
                        </div>
                    </div>
                    <div id="sparkline-3"></div>
                </div>
            </div>

            <!-- Categories Card -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="card fixed-card">
                    <div class="card-body">
                        <h5 class="text-muted">Categories</h5>
                        <div class="metric-value d-inline-block">
                            <h1 class="mb-1 text-primary">{{ number_format($totalCategories) }}</h1>
                        </div>
                        <div class="metric-label d-inline-block float-right text-success">
                            <i class="fa fa-fw fa-caret-up"></i><span>5.27%</span> <!-- Placeholder for change percentage -->
                        </div>
                    </div>
                    <div id="sparkline-1"></div>
                </div>
            </div>
        </div>


       
        <div class="row">
             <!-- LINE GRAPHS -->
            <!-- Revenue Section -->
            <div class="col-xl-8 col-lg-12 col-md-8 col-sm-12 col-12">
                <div class="card">
                    <h5 class="card-header">Revenue</h5>
                    <div class="card-body">
                        <canvas id="revenue" width="400" height="150"></canvas>
                    </div>
                    <div class="card-body border-top">
                        <div class="row">
                            <div class="offset-xl-1 col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 p-3">
                                <h4>Today's Earning: ₱{{ number_format($todaysEarnings, 2) }}</h4>
                            </div>

                            <div class="offset-xl-1 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 p-3">
                                <h2 class="font-weight-normal mb-3"><span>₱{{ number_format($currentWeekEarnings, 2) }}</span></h2>
                                <div class="mb-0 mt-3 legend-item">
                                    <span class="fa-xs legend-title" style="color: rgba(255, 99, 132, 1);">
                                        <i class="fa fa-fw fa-square-full"></i>
                                    </span>
                                    <span class="legend-text">Current Week</span>
                                </div>
                            </div>

                            <div class="offset-xl-1 col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 p-3">
                                <h2 class="font-weight-normal mb-3"><span>₱{{ number_format($previousWeekEarnings, 2) }}</span></h2>
                                <div class="text-muted mb-0 mt-3 legend-item">
                                    <span class="fa-xs legend-title" style="color: rgba(54, 162, 235, 1);">
                                        <i class="fa fa-fw fa-square-full"></i>
                                    </span>
                                    <span class="legend-text">Previous Week</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>



            @php
            // A diverse, high-contrast color palette
            $colorPalette = [
            '#FF5733', // Red-Orange
            '#FFD700', // Gold/Yellow
            '#4AB0E5', // Light Blue
            '#FF8C00', // Orange
            '#2E8B57', // Sea Green
            '#6A5ACD', // Slate Blue
            '#E76829', // Deep Orange
            ];

            $colors = [];
            foreach ($salesByCategory as $index => $category) {
            $colors[$category->category_name] = $colorPalette[$index % count($colorPalette)];
            }
            @endphp


            <!-- Total Sale Section -->
            <div class="col-xl-4 col-lg-12 col-md-4 col-sm-12 col-12">
                <div class="card">
                    <h5 class="card-header">Total Sale</h5>
                    <div class="card-body">
                        <canvas id="total-sale" width="220" height="155"></canvas>
                        <div class="chart-widget-list">
                            @foreach ($salesByCategory as $index => $category)
                            <p>
                                <span class="fa-xs legend-title" style="color: {{ $colors[$category->category_name] ?? '#cccccc' }};">
                                    <i class="fa fa-fw fa-square-full"></i>
                                </span>
                                <span class="legend-text">{{ $category->category_name }}</span>
                                <span class="float-right">₱{{ number_format($category->total_sales, 2) }}</span>
                            </p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <h5 class="card-header">Top Selling Products</h5>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr class="border-0">
                                        <th class="border-0">#</th>
                                        <th class="border-0">Product Name</th>
                                        <th class="border-0">Quantity</th>
                                        <th class="border-0">Price</th>
                                        <th class="border-0">Order Time</th>
                                        <th class="border-0">Customer</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($topSellingProducts as $index => $product)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $product->product_name }}</td>
                                        <td>{{ $product->quantity }}</td>
                                        <td>₱{{ number_format($product->price, 2) }}</td>
                                        <td>{{ $product->order_time }}</td>
                                        <td>{{ $product->customer_name }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No data available</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7">
                                            <a href="{{ route('admins.adminproductsreport') }}" class="btn btn-outline-light float-right">View Details</a>
                                        </td>
                                    </tr>
                                </tfoot>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    Copyright © 2018 Concept. All rights reserved. Dashboard by <a href="https://colorlib.com/wp/">Colorlib</a>.
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="text-md-right footer-links d-none d-sm-block">
                        <a href="javascript: void(0);">About</a>
                        <a href="javascript: void(0);">Support</a>
                        <a href="javascript: void(0);">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('scripts')
<script>
    // Prepare dynamic data for the Sales Pie Chart
    window.salesData = {
        labels: @json($salesByCategory->pluck('category_name')), // Category names for labels
        data: @json($salesByCategory->pluck('total_sales')), // Total sales for each category
        colors: @json(
            array_map(
                function ($name) use ($colors) {
                    return $colors[$name] ?? '#cccccc'; // Default to gray if no color is defined
                },
                $salesByCategory->pluck('category_name')->toArray() // Convert labels to an array
            )
        ),
    };

    // Prepare dynamic data for the Revenue Line Chart
    window.revenueData = {
        currentWeekRevenue: @json($currentWeekRevenue),
        previousWeekRevenue: @json($previousWeekRevenue),
    };
</script>
@endsection

@endsection