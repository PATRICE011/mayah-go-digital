@extends('admins.layout')

@section('title', 'Mayah Store - Stock In Report')

@section('content')
@include('admins.adminheader', ['activePage' => 'stockin'])

<div class="dashboard-wrapper">
    <div class="container-fluid dashboard-content">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h3 class="mb-2">Stock In Report</h3>

                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#" class="breadcrumb-link">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="#" class="breadcrumb-link">Reports</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="#" class="breadcrumb-link">Stock In Report</a>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-end align-items-center">
                        <!-- Search Input -->
                        <div class="mr-2" style="width: 200px;">
                            <form action="{{ url()->current() }}" method="GET">
                                <input type="text" name="search" class="form-control form-control-sm" value="{{ $search ?? '' }}" placeholder="Search by Product ID or Name">
                        </div>

                        <!-- Date Range Filter -->
                        <div class="mr-2 d-flex align-items-center" style="width: 350px;">
                            <input type="date" name="fromDate" class="form-control form-control-sm" style="width: 150px;" value="{{ $fromDate ?? '' }}" placeholder="From Date">
                            <span class="mx-2">to</span>
                            <input type="date" name="toDate" class="form-control form-control-sm" style="width: 150px;" value="{{ $toDate ?? '' }}" placeholder="To Date">
                        </div>

                        <button type="submit" class="btn btn-sm btn-outline-danger mr-2">
                            <i class="fa fa-search"></i> Filter
                        </button>
                        </form>

                        <!-- Export Button -->
                        <a href="{{ route('export.stock_in_report', ['search' => $search, 'fromDate' => $fromDate, 'toDate' => $toDate]) }}" class="btn btn-sm btn-outline-primary ml-2">
                            <i class="fa fa-download"></i> Export
                        </a>
                    </div>



                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr class="border-0">
                                        <th class="border-0">#</th>
                                        <th class="border-0">Product ID</th>
                                        <th class="border-0">Product Name</th>
                                        <th class="border-0">Category</th>
                                        <th class="border-0">Quantity In Stock</th>
                                        <th class="border-0">Unit Price</th>
                                        <th class="border-0">Date</th>
                                        <th class="border-0">Last Re Stock Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stockInReports as $report)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $report->product_id }}</td>
                                        <td>{{ $report->product_name }}</td>
                                        <td>{{ $report->category_name }}</td> <!-- Display category name instead of category_id -->

                                        <td>{{ $report->product_stocks }}</td> <!-- This will show the stock quantity from the 'products' table -->

                                        <td>{{ number_format($report->product_price, 2) }}</td>

                                        <!-- Format the 'created_at' and 'last_restock_date' fields -->
                                        <td>{{ \Carbon\Carbon::parse($report->created_at)->format('Y-m-d') }}</td>

                                        <td>
                                            <!-- Check if 'last_restock_date' is not null and parse it, otherwise show 'N/A' -->
                                            {{ $report->last_restock_date ? \Carbon\Carbon::parse($report->last_restock_date)->format('Y-m-d') : 'N/A' }}
                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <!-- Pagination Links -->
                        <div class="card-footer">
                            <div class="d-flex justify-content-end">
                                <nav aria-label="Stock In Report Pagination">
                                    <ul class="pagination justify-content-end">
                                        <!-- Previous Page Link -->
                                        @if ($stockInReports->onFirstPage())
                                        <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                                        @else
                                        <li class="page-item"><a class="page-link" href="{{ $stockInReports->previousPageUrl() }}">&laquo;</a></li>
                                        @endif

                                        <!-- Page Number Links -->
                                        @foreach ($stockInReports->getUrlRange(1, $stockInReports->lastPage()) as $page => $url)
                                        <li class="page-item {{ $stockInReports->currentPage() == $page ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                        @endforeach

                                        <!-- Next Page Link -->
                                        @if ($stockInReports->hasMorePages())
                                        <li class="page-item"><a class="page-link" href="{{ $stockInReports->nextPageUrl() }}">&raquo;</a></li>
                                        @else
                                        <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                                        @endif
                                    </ul>
                                </nav>
                            </div>
                        </div>


                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection