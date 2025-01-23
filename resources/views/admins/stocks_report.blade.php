@extends('admins.layout')
@section('title', 'Mayah Store - Stocks Report')
@section('content')
@include('admins.adminheader', ['activePage' => 'stocksreport'])

<style>
    .horizontal-layout {
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
    }
    .card {
        flex: 1 1 30%;
        margin: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .card-body {
        overflow-x: auto;
    }
    .table-responsive {
        min-width: 360px;
    }
    .search-box {
        margin-bottom: 20px;
    }
    .export-btn {
        text-align: right;
        margin-top: 20px;
    }
</style>

<div class="dashboard-wrapper">
    <div class="container-fluid dashboard-content">
    <div class="export-btn">
            <button onclick="window.location=''" class="btn btn-sm btn-outline-danger mr-2">
                <i class="fa fa-file-export"></i> Export
            </button>
        </div>
        <div class="horizontal-layout">
            <!-- Stock In Table with Search -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    Stock In
                </div>
                <div class="card-body">
                    <form action="{{ url()->current() }}" method="GET" class="search-box">
                        <input type="text" name="searchIn" class="form-control mb-2" placeholder="Search in Stock In..." value="{{ request('searchIn') }}">
                        <!-- <button type="submit" class="btn btn-primary">Search</button> -->
                    </form>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Item Name</th>
                                    <th>In Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($movement_in as $item)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item->stock_in_date)->format('m/d/Y') }}</td>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->stock_in_quantity }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $movement_in->appends(['searchIn' => request('searchIn')])->links() }}
                </div>
            </div>

            <!-- Stock Out Table with Search -->
            <div class="card">
                <div class="card-header bg-danger text-white">
                    Stock Out
                </div>
                <div class="card-body">
                    <form action="{{ url()->current() }}" method="GET" class="search-box">
                        <input type="text" name="searchOut" class="form-control mb-2" placeholder="Search in Stock Out..." value="{{ request('searchOut') }}">
                        <!-- <button type="submit" class="btn btn-primary">Search</button> -->
                    </form>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Item Name</th>
                                    <th>Out Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($movement_out as $item)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item->stock_out_date)->format('m/d/Y') }}</td>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->stock_out_quantity }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $movement_out->appends(['searchOut' => request('searchOut')])->links() }}
                </div>
            </div>

            <!-- Current Stock Table with Search -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Current Stock
                </div>
                <div class="card-body">
                    <form action="{{ url()->current() }}" method="GET" class="search-box">
                        <input type="text" name="searchStock" class="form-control mb-2" placeholder="Search in Current Stock..." value="{{ request('searchStock') }}">
                        <!-- <button type="submit" class="btn btn-primary">Search</button> -->
                    </form>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Balance Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($current_stock as $stock)
                                <tr>
                                    <td>{{ $stock->product_name }}</td>
                                    <td>{{ $stock->balance_quantity }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $current_stock->appends(['searchStock' => request('searchStock')])->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
