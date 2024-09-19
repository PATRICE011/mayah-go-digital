<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <!-- Latest Bootstrap CSS (Bootstrap 5) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .order-wrapper {
            border: 2px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 20px;
            margin-bottom: 30px;
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
        }

        .order-number {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 15px;
            color: #343a40;
        }

        .order-card {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 15px;
            margin-bottom: 20px;
        }

        .order-image {
            max-width: 100px;
            height: auto;
            border-radius: 0.375rem;
        }

        .order-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            position: relative;
        }

        .order-info {
            flex-grow: 1;
            margin-left: 20px;
        }

        .amount-payable {
            margin-bottom: 10px;
            font-weight: bold;
        }

        .order-actions {
            margin-top: 15px;
            text-align: right;
        }

        .order-price {
            font-size: 1rem;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2>My Orders</h2>
    <!-- Order Status Tabs -->
    <ul class="nav nav-tabs" id="orderTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="toPay-tab" data-bs-toggle="tab" href="#toPay" role="tab" aria-controls="toPay" aria-selected="true">To Pay</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="readyForPickup-tab" data-bs-toggle="tab" href="#readyForPickup" role="tab" aria-controls="readyForPickup" aria-selected="false">Ready for Pickup</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="returnRefund-tab" data-bs-toggle="tab" href="#returnRefund" role="tab" aria-controls="returnRefund" aria-selected="false">Return/Refund</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="completed-tab" data-bs-toggle="tab" href="#completed" role="tab" aria-controls="completed" aria-selected="false">Completed</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="cancelled-tab" data-bs-toggle="tab" href="#cancelled" role="tab" aria-controls="cancelled" aria-selected="false">Cancelled</a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-3" id="orderTabsContent">
        <!-- To Pay Tab -->
        <div class="tab-pane fade show active" id="toPay" role="tabpanel" aria-labelledby="toPay-tab">
            <div class="order-wrapper">
                <div class="order-number">Order #12345</div> <!-- Sample Order Number -->
                <div class="order-card">
                    <div class="d-flex align-items-start position-relative">
                        <!-- Product Image -->
                        <img src="https://via.placeholder.com/100" class="order-image" alt="Product Image">
                        <!-- Product Details -->
                        <div class="order-info">
                            <h5>Product Name</h5>
                            <p><strong>Category:</strong> Electronics</p>
                            <p><strong>Quantity:</strong> 2</p>
                            <p class="order-price"><strong>Price per item:</strong> $31</p>
                        </div>
                    </div>
                </div>
                <div class="order-card">
                    <div class="d-flex align-items-start position-relative">
                        <!-- Product Image -->
                        <img src="https://via.placeholder.com/100" class="order-image" alt="Product Image">
                        <!-- Product Details -->
                        <div class="order-info">
                            <h5>Product Name</h5>
                            <p><strong>Category:</strong> Electronics</p>
                            <p><strong>Quantity:</strong> 2</p>
                            <p class="order-price"><strong>Price per item:</strong> $31</p>
                        </div>
                    </div>
                </div>
                <!-- Amount Payable and Pay Now Button Outside the Card -->
                <div class="order-actions">
                    <p class="amount-payable">Amount Payable: $62</p> <!-- Total price for quantity -->
                    <a href="#" class="btn btn-primary">Pay Now</a>
                </div>
            </div>
        </div>

        <!-- Ready for Pickup Tab -->
        <div class="tab-pane fade" id="readyForPickup" role="tabpanel" aria-labelledby="readyForPickup-tab">
            <div class="order-wrapper">
                <div class="order-number">Order #98765</div> <!-- Sample Order Number -->
                <div class="order-card">
                    <div class="d-flex align-items-start position-relative">
                        <!-- Product Image -->
                        <img src="https://via.placeholder.com/100" class="order-image" alt="Product Image">
                        <!-- Product Details -->
                        <div class="order-info">
                            <h5>Product Name</h5>
                            <p><strong>Category:</strong> Home Appliances</p>
                            <p><strong>Quantity:</strong> 1</p>
                            <p class="order-price"><strong>Price per item:</strong> $200</p>
                        </div>
                    </div>
                </div>
                <!-- Pickup Time Information -->
                <div class="order-actions">
                    <p class="amount-payable">Ready for Pickup at 4 PM</p>
                </div>
            </div>
        </div>

        <!-- Return/Refund Tab -->
        <div class="tab-pane fade" id="returnRefund" role="tabpanel" aria-labelledby="returnRefund-tab">
            <div class="order-wrapper">
                <div class="order-number">Order #65432</div> <!-- Sample Order Number -->
                <div class="order-card">
                    <div class="d-flex align-items-start position-relative">
                        <!-- Product Image -->
                        <img src="https://via.placeholder.com/100" class="order-image" alt="Product Image">
                        <!-- Product Details -->
                        <div class="order-info">
                            <h5>Product Name</h5>
                            <p><strong>Category:</strong> Fashion</p>
                            <p><strong>Quantity:</strong> 3</p>
                            <p class="order-price"><strong>Price per item:</strong> $30</p>
                        </div>
                    </div>
                </div>
                <!-- Refund Information -->
                <div class="order-actions">
                    <p class="amount-payable">Refund Requested: $90</p> <!-- Total refund amount -->
                </div>
            </div>
        </div>

        <!-- Completed Tab -->
        <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
            <div class="order-wrapper">
                <div class="order-number">Order #34567</div> <!-- Sample Order Number -->
                <div class="order-card">
                    <div class="d-flex align-items-start position-relative">
                        <!-- Product Image -->
                        <img src="https://via.placeholder.com/100" class="order-image" alt="Product Image">
                        <!-- Product Details -->
                        <div class="order-info">
                            <h5>Product Name</h5>
                            <p><strong>Category:</strong> Grocery</p>
                            <p><strong>Quantity:</strong> 5</p>
                            <p class="order-price"><strong>Price per item:</strong> $12</p>
                        </div>
                    </div>
                </div>
                <!-- Completion Information -->
                <div class="order-actions">
                    <p class="amount-payable">Completed on 10th Sept</p>
                </div>
            </div>
        </div>

        <!-- Cancelled Tab -->
        <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
            <div class="order-wrapper">
                <div class="order-number">Order #24680</div> <!-- Sample Order Number -->
                <div class="order-card">
                    <div class="d-flex align-items-start position-relative">
                        <!-- Product Image -->
                        <img src="https://via.placeholder.com/100" class="order-image" alt="Product Image">
                        <!-- Product Details -->
                        <div class="order-info">
                            <h5>Product Name</h5>
                            <p><strong>Category:</strong> Books</p>
                            <p><strong>Quantity:</strong> 2</p>
                            <p class="order-price"><strong>Price per item:</strong> $15</p>
                        </div>
                    </div>
                </div>
                <!-- Cancellation Information -->
                <div class="order-actions">
                    <p class="amount-payable">Cancelled on 9th Sept</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Latest Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
