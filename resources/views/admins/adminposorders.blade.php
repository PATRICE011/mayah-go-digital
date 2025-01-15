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
                                            <div class="action__btn">
                                                <!-- VIEW BUTTON -->
                                                <button class="edit" data-toggle="modal" data-target="#orderDetailsModal">
                                                    <i class="ri-mail-line"></i>
                                                </button>

                                                <!-- VIEW MODAL -->
                                                <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div class="mb-4">
                                                                    <h6><strong>Order Details</strong></h6>
                                                                    <p>Order ID: <span class="text-primary">#5660681</span></p>
                                                                    <p>Order Date: <span class="text-secondary">09-01-2025</span></p>
                                                                    <p>Order Status: <span class="text-warning">Pending</span></p>
                                                                    <p>Payment Status: <span class="text-success">Paid</span></p>
                                                                    <p>Payment Method: <span class="text-warning">Gcash</span></p>
                                                                </div>

                                                                <div class="mb-4">
                                                                    <h6><strong>Order Summary</strong></h6>
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Product</th>
                                                                                <th>Price</th>
                                                                                <th>Quantity</th>
                                                                            </tr>
                                                                        </thead>

                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Bread Stix</td>
                                                                                <td>₱16.00</td>
                                                                                <td>1</td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td>Fita</td>
                                                                                <td>₱10.00</td>
                                                                                <td>1</td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td>C2 Green</td>
                                                                                <td>₱25.00</td>
                                                                                <td>2</td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td>C2 Yellow</td>
                                                                                <td>₱25.00</td>
                                                                                <td>1</td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td colspan="2"><strong>Subtotal</strong></td>
                                                                                <td>₱101.00</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>

                                                                <!-- Form Section -->
                                                                <div class="mb-4">
                                                                    <form id="viewForm">
                                                                        <div class="form-group mb-3">
                                                                            <label for="viewStatus">Status</label>
                                                                            <select class="form-control" id="viewStatus">
                                                                                <option value="pending">Pending</option>
                                                                                <option value="confirmed">Confirmed</option>
                                                                                <option value="readyForPickup">Ready For Pickup</option>
                                                                                <option value="completed">Completed</option>
                                                                            </select>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>

                                                            <!-- Footer -->
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-primary" onclick="applyFilters()">Apply Changes</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <!-- ARCHIVE BUTTON -->
                                                <button class="archive" data-bs-toggle="modal" data-bs-target="#archiveModal">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>

                                                <!-- ARCHIVE MODAL -->
                                                <div class="modal fade" id="archiveModal" tabindex="-1" aria-labelledby="archiveModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="archiveModalLabel">Archive Item</h5>
                                                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to archive this item? This action cannot be undone.
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="button" class="btn btn-danger">Archive</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="8" class="text-right">
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination justify-content-end mb-0">
                                                    <li class="page-item disabled">
                                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                                            < </a>
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