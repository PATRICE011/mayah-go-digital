@extends('admins.layout')
@section('title', 'Mayah Store - Admin Products')
@section('content')
@include('admins.adminheader', ['activePage' => 'posreport'])

<div class="dashboard-wrapper">
    <div class="container-fluid dashboard-content">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h3 class="mb-2">Sales Report</h3>

                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#" class="breadcrumb-link">Dashboard</a>
                                </li>

                                <li class="breadcrumb-item">
                                    <a href="#" class="breadcrumb-link">Reports</a>
                                </li>

                                <li class="breadcrumb-item active" aria-current="page">
                                    Sales Report
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Export -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-end align-items-center">
                        <!-- Search -->
                        <div class="mr-2" style="width: 200px;">
                            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search...">
                        </div>
                        <!-- Date Range Filter -->
                        <div class="mr-2 d-flex align-items-center" style="width: 350px;">
                            <input type="date" id="fromDate" class="form-control form-control-sm" style="width: 150px;" placeholder="From Date">
                            <span class="mx-2">to</span>
                            <input type="date" id="toDate" class="form-control form-control-sm" style="width: 150px;" placeholder="To Date">
                        </div>
                        <!-- Export Button -->
                        <a href="{{ route('admins.export.report') }}" class="btn btn-sm btn-outline-danger">
                            <i class="fa fa-file-export"></i> Export
                        </a>

                    </div>

                    <!-- Sales Report Table -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr class="border-0">
                                        <th class="border-0">#</th>
                                        <th class="border-0">Product Name</th>
                                        <th class="border-0">Quantity</th>
                                        <th class="border-0">Unit Price</th>
                                        <th class="border-0">Amount</th>
                                        <th class="border-0">Date</th>
                                        <th class="border-0">Customer</th>
                                    </tr>
                                </thead>
                                <tbody id="salesReportBody">
                                    @forelse($salesReport as $report)
                                    <tr>
                                        <td>{{ $report['id'] }}</td>
                                        <td>{{ $report['product_name'] }}</td>
                                        <td>{{ $report['quantity'] }}</td>
                                        <td>₱{{ $report['unit_price'] }}</td>
                                        <td>₱{{ $report['amount'] }}</td>
                                        <td>{{ $report['date'] }}</td>
                                        <td>{{ $report['customer'] }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No sales data available.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $salesReport->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        // Export Button Click Event
        $('#exportSalesReportBtn').on('click', function() {
            window.location.href = '';
        });
    });
</script>
@endsection
@endsection