@extends('admins.layout')
@section('title', 'Mayah Store - Admin Online Orders')
@section('content')
@include('admins.adminheader', ['activePage' => 'onlineorders'])
<style>
    /* Base Styles & Reset */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        line-height: 1.5;
        color: #333;
    }

    .modal-container {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        align-items: center;
        justify-content: center;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
    }

    .modal-container.show {
        display: flex;
    }

    .modal-content {
        width: 100%;
        max-width: 800px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        max-height: 90vh;
        overflow-y: auto;
    }

    /* Modal Header */
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px 28px;
        border-bottom: 1px solid #f0f0f0;
    }

    .modal-title {
        font-size: 20px;
        font-weight: 600;
        color: #1a1a1a;
    }

    .close-button {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #666;
        height: 32px;
        width: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
    }

    .close-button:hover {
        background-color: #f5f5f5;
    }

    /* Order Banner */
    .order-banner {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #f7f9fc;
        padding: 16px 28px;
        margin-bottom: 24px;
    }

    .order-id,
    .order-status {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .label {
        font-size: 14px;
        color: #666;
    }

    .value {
        font-size: 15px;
        color: #333;
    }

    .highlight {
        color: #2563eb;
        font-weight: 500;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
        color: #f0f0f0
    }

    .bg-orange {
        background-color: #fd7e14 !important;
        /* Bootstrap's orange */
        color: #fff !important;
    }

    .pending {
        background-color: black;
        /* Dark mustard yellow */
        color: white;
    }


    /* Two Column Layout */
    .content-columns {
        display: flex;
        gap: 24px;
        padding: 0 28px 24px;
    }

    .column {
        flex: 1;
        min-width: 0;
        /* Prevent flex items from overflowing */
    }

    @media (max-width: 768px) {
        .content-columns {
            flex-direction: column;
        }
    }

    /* Section Cards */
    .section-card {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        border: 1px solid #eaedf3;
        height: 100%;
    }

    .section-title {
        padding: 16px 20px;
        border-bottom: 1px solid #eaedf3;
        font-size: 14px;
        font-weight: 600;
        color: #4b5563;
        letter-spacing: 0.5px;
    }

    /* Order Information Grid */
    .info-grid {
        padding: 16px 20px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #64748b;
        font-size: 14px;
    }

    .info-value {
        font-size: 14px;
        font-weight: 500;
    }

    .payment-badge {
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .payment-badge.paid {
        background-color: #d1fae5;
        color: #047857;
    }

    /* Order Table */
    .order-table {
        width: 100%;
        border-collapse: collapse;
    }

    .order-table th {
        text-align: left;
        padding: 12px 20px;
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        border-bottom: 1px solid #eaedf3;
    }

    .order-table td {
        padding: 14px 20px;
        font-size: 14px;
        border-bottom: 1px solid #f0f0f0;
    }

    .order-table tfoot td {
        padding-top: 16px;
        font-weight: 600;
    }

    .subtotal-label {
        text-align: right;
        color: #64748b;
    }

    .subtotal-value {
        font-weight: 600;
    }

    /* Update Status Section */
    .update-section {
        background-color: #f7f9fc;
        border-radius: 0 0 16px 16px;
        padding: 0 0 28px;
        margin-top: 8px;
    }

    .update-form {
        display: flex;
        gap: 16px;
        padding: 16px 28px;
    }

    .select-wrapper {
        position: relative;
        flex-grow: 1;
    }

    .select-wrapper::after {
        content: "▼";
        font-size: 12px;
        color: #64748b;
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .status-select {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 15px;
        appearance: none;
        background-color: white;
        color: #1a1a1a;
    }

    .update-button {
        padding: 12px 24px;
        background-color: #3b82f6;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .update-button:hover {
        background-color: #2563eb;
    }

    /* Focus States for Accessibility */
    .status-select:focus,
    .update-button:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
    }
</style>

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
<div class="modal-container" id="orderDetailsModal">
    <div class="modal-content">
        <!-- Modal Header with close button -->
        <div class="modal-header">
            <h2 class="modal-title" id="modalOrderTitle">Order Details</h2>
            <button class="close-button" aria-label="Close" id="closeOrderModal">×</button>
        </div>

        <!-- Order Banner -->
        <div class="order-banner">
            <div class="order-id">
                <span class="label">Order ID:</span>
                <span class="value highlight" id="orderCustomID">#</span>
            </div>
            <div class="order-status">
                <span class="label">Status:</span>
                <span class="status-badge " id="orderStatus">Pending</span>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="content-columns">
            <!-- Left Column: Order Information -->
            <div class="column">
                <div class="section-card">
                    <h3 class="section-title">ORDER INFORMATION</h3>
                    <div class="info-grid">
                        <div class="info-row">
                            <span class="info-label">Date:</span>
                            <span class="info-value" id="orderDate">--</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Payment Status:</span>
                            <span class="payment-badge paid" id="paymentStatus">--</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Payment Method:</span>
                            <span class="info-value" id="paymentMethod">--</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Order Summary -->
            <div class="column">
                <div class="section-card">
                    <h3 class="section-title">ORDER SUMMARY</h3>
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody id="orderSummaryBody">
                            <!-- dynamic content -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="subtotal-label">Subtotal</td>
                                <td class="subtotal-value" id="orderSubtotal">₱0.00</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Update Status Section -->
        <div class="update-section">
            <h3 class="section-title">UPDATE STATUS</h3>
            <div class="update-form">
                <div class="select-wrapper">
                    <select class="status-select" id="updateStatus" aria-label="Select new status">
                        <option value="" disabled selected>Select new status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="readyForPickup">Ready For Pickup</option>
                        <option value="completed">Completed</option>
                        <option value="returned">Returned</option>
                        <option value="refunded">Refunded</option>
                    </select>
                </div>
                <button class="update-button" id="applyOrderChanges">Update</button>
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