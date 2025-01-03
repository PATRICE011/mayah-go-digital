@extends('admins.layout')
@section('title', 'Mayah Store - Admin POS Orders')
@section('content')
@include('admins.adminheader', ['activePage' => 'posorders'])

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
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-end align-items-center">
                        <div class="mr-2" style="width: 200px;">
                            <input type="text" class="form-control form-control-sm" placeholder="Search...">
                        </div>

                        <button class="btn btn-sm btn-outline-warning mr-2" data-toggle="modal" data-target="#filterModal">
                            <i class="fa fa-filter"></i> Filter
                        </button>

                        <!-- Filter Modal -->
                        <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="filterModalLabel">Filter POS Orders</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Add Filter Fields Here -->
                                        <form id="filterForm">
                                            <div class="form-group">
                                                <label for="filteCategory">Order ID</label>
                                                <input type="number" class="form-control" id="filterOrderID" placeholder="Enter Order ID" min="0">
                                            </div>

                                            <div class="form-group">
                                                <label for="filteCategory">Customer Name</label>
                                                <input type="text" class="form-control" id="filterName" placeholder="Enter customer name">
                                            </div>

                                            <div class="form-group">
                                                <label for="filteCategory">Date</label>
                                                <input type="date" class="form-control" id="filterDate">
                                            </div>


                                            <div class="form-group">
                                                <label for="filterStatus">Status</label>
                                                <select class="form-control" id="filterStatus">
                                                    <option value="">All</option>
                                                    <option value="active">Picked Up</option>
                                                    <option value="active">Completed</option>
                                                    <option value="inactive">Packed</option>
                                                </select>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" onclick="applyFilters()">Apply Filters</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-sm btn-outline-danger mr-2">
                            <i class="fa fa-file-export"></i> Export
                        </button>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr class="border-0">
                                        <th class="border-0">#</th>
                                        <th class="border-0">Order ID</th>
                                        <th class="border-0">Customer</th>
                                        <th class="border-0">Amount</th>
                                        <th class="border-0">Date</th>
                                        <th class="border-0">Status</th>
                                        <th class="border-0">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>52174</td>
                                        <td>John Doe</td>
                                        <td>₱20.00</td>
                                        <td>04:42 PM, 19-11-2024</td>
                                        <td>Picked Up</td>
                                        <td>
                                            <i class="ri-mail-line" style="margin-right: 0.5rem;"></i>
                                            <i class="ri-delete-bin-line"></i>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="8" class="text-right">
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination justify-content-end mb-0">
                                                    <li class="page-item disabled">
                                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true"> < </a>
                                                    </li>

                                                    <li class="page-item active">
                                                        <a class="page-link" href="#">1 <span class="sr-only">(current)</span></a>
                                                    </li>

                                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                    <li class="page-item"><a class="page-link" href="#">3</a></li>

                                                    <li class="page-item">
                                                        <a class="page-link" href="#"> > </a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection