@extends('admins.layout')
@section('title', 'Mayah Store - Admin Online Orders')
@section('content')
@include('admins.adminheader', ['activePage' => 'onlineorders'])

<div class="dashboard-wrapper">
    <div class="container-fluid dashboard-content">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h3 class="mb-2">Online Orders</h3>
                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Online Orders</a></li>
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
                            <input id="searchOrder" type="text" class="form-control form-control-sm" placeholder="Search...">
                        </div>
                        <button class="btn btn-sm btn-outline-warning mr-2" data-toggle="modal" data-target="#filterModal">
                            <i class="fa fa-filter"></i> Filter
                        </button>

                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Order ID</th>
                                        <th>Customer Name</th>
                                        <th>Total Amount</th>
                                        <th>Order Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="orderTableBody">
                                    <!-- Dynamic content will load here -->
                                </tbody>

                            </table>

                            <div class="pagination-container mb-3 mt-3 mr-3">
                                {{ $orders->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Filter Online Orders</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="filterForm">
                    <div class="form-group">
                        <label for="filterOrderID">Order ID</label>
                        <input type="number" class="form-control" id="filterOrderID" placeholder="Enter Order ID" min="0">
                    </div>
                    <div class="form-group">
                        <label for="filterDate">Date</label>
                        <input type="date" class="form-control" id="filterDate">
                    </div>
                    <div class="form-group">
                        <label for="filterStatus">Status</label>
                        <select class="form-control" id="filterStatus">
                            <option value="">All</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>

                            <option value="readyForPickup">Ready for pickup</option>

                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="applyFilters">Apply Filters</button>
            </div>


        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <!-- Order Details Section -->
                <div class="mb-4">
                    <h6><strong>Order Details</strong></h6>
                    <p>Order ID: <span class="text-primary" id="orderCustomID">N/A</span></p>
                    <p>Order Date: <span class="text-secondary" id="orderDate">N/A</span></p>
                    <p>Order Status: <span class="text-warning" id="orderStatus">N/A</span></p>
                    <p>Payment Status: <span class="text-success" id="paymentStatus">N/A</span></p>
                    <p>Payment Method: <span class="text-info" id="paymentMethod">N/A</span></p>
                </div>

                <!-- Order Summary Section -->
                <div class="mb-4">
                    <h6><strong>Order Summary</strong></h6>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <!-- <th>Total</th> -->
                            </tr>
                        </thead>
                        <tbody id="orderSummaryTable">
                            <tr>
                                <td colspan="4" class="text-center">No items found.</td>
                            </tr>
                        </tbody>
                        <!-- <tfoot>
                            <tr>
                                <td colspan="3"><strong>Subtotal</strong></td>
                                <td id="orderSubtotal">₱0.00</td>
                            </tr>
                        </tfoot> -->
                    </table>
                </div>

                <!-- Order Status Update Form -->
                <div class="mb-4">
                    <form id="updateOrderForm">
                        <div class="form-group">
                            <label for="updateStatus">Update Status</label>
                            <select class="form-control" id="updateStatus">
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="readyForPickup">Ready For Pickup</option>
                                <option value="completed">Completed</option>
                                <option value="completed">Returned</option>
                                <option value="completed">Refunded</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" id="applyOrderChanges">Apply Changes</button>
            </div>
        </div>
    </div>
</div>


<!-- Archive Modal -->
<div class="modal fade" id="archiveModal" tabindex="-1" role="dialog" aria-labelledby="archiveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div the="modal-header">
                <h5 class="modal-title" id="archiveModalLabel">Delete Order</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this item? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="{{ asset('assets/js/online_orders.js') }}?v={{ time() }}"></script>
@endsection
@endsection