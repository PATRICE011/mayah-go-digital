@extends('admins.layout')
@section('title', 'Mayah Store - Admin POS')
@section('styles')
<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f8f9fa;
    }

    .dashboard-wrapper {
        padding: 20px;
    }

    .category-item {
        cursor: pointer;
    }

    /* .category-item:hover {
        background-color: #e9ecef;
    } */

    .category-item.active {
        background-color: #007bff;
        /* Highlight the active category with blue */
        color: white;
        /* Change text color to white */
    }

    /* .category-item:hover {
        background-color: #e9ecef;
    } */

    .product-card {
        transition: transform 0.2s;
        cursor: pointer;
        border-radius: 8px;
        overflow: hidden;
    }

    .product-card:hover {
        transform: scale(1.05);
    }

    .checkout-summary {
        border-top: 1px solid #dee2e6;
        padding-top: 10px;
    }

    .modal-header {
        background-color: #007bff;
        color: white;
    }

    .modal-footer {
        justify-content: space-between;
    }

    /* Checkout Section */

    .product-price {
        margin-top: 5px;
        margin-right: 10px;
    }

    .bottom-price {
        margin-right: 15px;
        font-size: 28px;
        color: black;
    }

    .checkout-section {
        display: flex;
        flex-direction: column;
        padding: 15px;
        max-height: 650px;
        /* Adjust the max height if necessary */
        overflow-y: auto;
        position: relative;
    }


    .cart-item {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
        margin-bottom: 10px;
        border-radius: 8px;
        background-color: #fff;
    }

    .cart-item .cart-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-weight: bold;
    }

    .cart-item .quantity-controls {
        display: flex;
        align-items: center;
    }

    .cart-item .quantity-controls button {
        padding: 6px 12px;
        font-size: 1rem;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        background-color: #f0f0f0;
        margin: 0 5px;
    }

    .cart-item .quantity-controls button:hover {
        background-color: #007bff;
        color: white;
    }

    .cart-item .cart-quantity {
        width: 40px;
        text-align: center;
        font-size: 1.1rem;
        margin: 0 5px;
    }

    .cart-item .delete-item {
        padding: 6px 12px;
        border: none;
        background-color: #e74c3c;
        color: red;
        cursor: pointer;
        border-radius: 5px;
    }

    .cart-item .delete-item:hover {
        background-color: #c0392b;
    }



    .checkout-footer {
        position: relative;
        background-color: #fff;
        padding: 15px 0;
        border-top: 1px solid #ddd;
        z-index: 10;
        margin-top: auto;
        /* This will push the footer to the bottom */
    }

    .total-label {
        font-weight: bold;
        font-size: 1.2rem;
    }


    .checkout-btn,
    #clear-cart-btn {
        width: 100%;
        margin-top: 10px;
        padding: 12px 0;
        font-size: 1.1rem;
        border-radius: 5px;
        border: none;
    }

    .checkout-btn {
        background-color: #28a745;
        color: white;
    }

    .checkout-btn:hover {
        background-color: #218838;
    }

    .checkout-btn {
        background-color: #28a745;
        color: white;
    }

    .checkout-btn:hover {
        background-color: #218838;
    }

    #clear-cart-btn {
        background-color: #dc3545;
        color: white;
    }

    #clear-cart-btn:hover {
        background-color: #c82333;
    }

    /* Search Bar Styles */
    #product-search {
        max-width: 180px;
    }

    #clear-search {
        max-width: 70px;
        margin-left: 5px;
    }

    /* Order History Styles */
    #order-history-table tr:hover {
        background-color: #f1f1f1;
        cursor: pointer;
    }

    .view-order-details {
        transition: all 0.2s;
    }

    .view-order-details:hover {
        transform: scale(1.05);
    }

    #order-details-section {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
    }

    /* Additional Styles for mobile responsiveness */
    @media (max-width: 768px) {
        .checkout-section {
            padding: 10px;
        }

        .cart-item {
            padding: 10px;
        }
    }
</style>
@endsection
@section('content')
@include('admins.adminheader', ['activePage' => 'pos'])

<div class="dashboard-wrapper">
    <div class="container-fluid dashboard-content">
        <div class="row">
            <!-- Sidebar Categories -->
            <div class="col-md-3 bg-white shadow-sm rounded p-3">
                <h5 class="text-primary mb-3">Categories</h5>
                <ul id="categories" class="list-group">
                    <!-- Categories will be dynamically loaded here -->
                </ul>
            </div>

            <!-- Main Products Section -->
            <div class="col-md-6">
                <h5 class="text-primary mb-3">Products</h5>
                <div class="input-group mb-3">
                    <input type="text" id="product-search" class="form-control form-control-sm" placeholder="Search products..." />
                    <button class="btn btn-outline-secondary btn-sm" type="button" id="clear-search">Clear</button>
                </div>

                <div id="products" class="row g-4">
                    <!-- Products will be dynamically loaded here -->
                </div>

                <!-- Pagination -->
                <nav id="pagination" class="mt-4 d-flex justify-content-center"></nav>
            </div>

            <!-- Checkout Section -->

            <div class="col-md-3 bg-white shadow-sm rounded p-3 checkout-section">

                <hr>
                <h5 class="text-primary mb-3">Checkout</h5>
                <ul id="cart-items" class="list-group mb-3"></ul>

                <!-- Checkout Footer -->
                <div class="checkout-footer">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="total-label">Total:</span>
                        <span id="cart-total" class="text-primary font-weight-bold bottom-price">₱0.00</span>
                    </div>
                    <button id="checkout-btn" class="btn checkout-btn" disabled>Checkout</button>
                    <button id="clear-cart-btn" class="btn" type="button">Clear Cart</button>
                    <button id="order-history-btn" class="btn btn-info" type="button" style="width: 100%; margin-top: 10px; padding: 12px 0; font-size: 1.1rem; border-radius: 5px; border: none;">
                        View Order History
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cash Paid Modal -->
<div class="modal fade" id="cashPaidModal" tabindex="-1" role="dialog" aria-labelledby="cashPaidModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cashPaidModalLabel">Enter Payment Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="payment-form">
                    <div class="form-group">
                        <label for="cash-paid" class="form-label">Cash Paid</label>
                        <input type="number" id="cash-paid" class="form-control" placeholder="Enter cash amount" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="submit-payment" type="button" class="btn btn-primary">Submit Payment</button>
            </div>
        </div>
    </div>
</div>

<!-- Order History Modal -->
<div class="modal fade" id="orderHistoryModal" tabindex="-1" role="dialog" aria-labelledby="orderHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderHistoryModalLabel">Order History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Total Amount</th>
                                <th>Cash Paid</th>
                                <th>Change</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="order-history-table">
                            <!-- Order history will be loaded here -->
                        </tbody>
                    </table>
                </div>
                <!-- Order Details Section -->
                <div id="order-details-section" style="display: none;">


                    <h5 class="mt-4 mb-3">Order Details</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="order-details-table">
                                <!-- Order details will be loaded here -->
                            </tbody>
                        </table>
                        <div class="text-end mt-3">
                            <button id="print-receipt-btn" class="btn btn-success" style="display: none;">
                                <i class="bi bi-printer"></i> Print Receipt
                            </button>
                        </div>
                    </div>

                </div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div> -->
        </div>
    </div>
</div>

@endsection
@section('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        // Load Categories
        function loadCategories() {
            $.ajax({
                url: '{{ route("categories.get") }}',
                type: 'GET',
                success: function(categories) {
                    let html = `<li class="list-group-item category-item" data-id="all">Show All</li>`;
                    categories.forEach(category => {
                        html += `<li class="list-group-item category-item" data-id="${category.id}">${category.category_name}</li>`;
                    });
                    $('#categories').html(html);
                },
                error: function() {
                    Swal.fire('Error', 'Failed to load categories.', 'error');
                }
            });
        }

        // Handle Category Click
        $(document).on('click', '.category-item', function() {
            // Remove the 'active' class from all category items
            $('.category-item').removeClass('active');
            // Add 'active' class to the clicked category
            $(this).addClass('active');

            const categoryId = $(this).data('id');
            loadProducts(categoryId, 1);
        });

        // Initial Load
        loadCategories();


        function renderPagination(pagination, categoryId, searchQuery) {
            if (!pagination || !pagination.last_page) return;

            let paginationHtml = '<nav aria-label="Page navigation">';
            paginationHtml += '<ul class="pagination justify-content-center">';

            // Previous button
            if (pagination.current_page > 1) {
                paginationHtml += `
            <li class="page-item">
                <a class="page-link" href="#" data-page="${pagination.current_page - 1}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>`;
            } else {
                paginationHtml += `
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>`;
            }

            // Page numbers
            for (let i = 1; i <= pagination.last_page; i++) {
                paginationHtml += `
            <li class="page-item ${pagination.current_page === i ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>`;
            }

            // Next button
            if (pagination.current_page < pagination.last_page) {
                paginationHtml += `
            <li class="page-item">
                <a class="page-link" href="#" data-page="${pagination.current_page + 1}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>`;
            } else {
                paginationHtml += `
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>`;
            }

            paginationHtml += '</ul></nav>';
            $('#pagination').html(paginationHtml);

            // Forcefully set active state
            setTimeout(() => {
                $('#pagination .page-item').removeClass('active');
                $(`#pagination .page-link[data-page="${pagination.current_page}"]`)
                    .parent()
                    .addClass('active');
            }, 0);

            // Attach click event handlers
            $('#pagination .page-link').on('click', function(e) {
                e.preventDefault();
                const page = $(this).data('page');
                if (page) {
                    loadProducts('all', page);
                }
            });
        }

        // Modify loadProducts to use the new rendering
        function loadProducts(categoryId = 'all', page = 1, searchQuery = '') {
            $('#products').html('<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>');
            $.ajax({
                url: '{{ route("products.search") }}',
                type: 'GET',
                data: {
                    category_id: categoryId,
                    page: page,
                    search: searchQuery
                },
                success: function(response) {
                    const products = response.products;
                    const pagination = response.pagination;

                    // Render products (previous logic remains the same)
                    let html = '';
                    products.forEach(product => {
                        if (product.product_stocks > 0) {
                            html += `
                    <div class="col-lg-4 col-md-6">
                        <div class="card shadow-sm product-card border-0" data-id="${product.id}">
                            <img src="/assets/img/${product.product_image}" class="card-img-top" alt="${product.product_name}">
                            <div class="card-body text-center">
                                <h6 class="card-title font-weight-bold text-dark">${product.product_name}</h6>
                                <p class="card-text text-success">₱${product.product_price.toFixed(2)}</p>
                            </div>
                        </div>
                    </div>`;
                        }
                    });
                    $('#products').html(html);

                    // Render pagination with current category and search
                    renderPagination(pagination, categoryId, searchQuery);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire('Error', 'Failed to load products.', 'error');
                }
            });
        }


        // Handle Search Input
        $('#product-search').on('keyup', function() {
            const searchQuery = $(this).val();
            loadProducts('all', 1, searchQuery);
        });

        // Clear Search Input
        $('#clear-search').on('click', function() {
            $('#product-search').val('');
            loadProducts('all', 1);
        });

        // Handle Category Click
        $(document).on('click', '.category-item', function() {
            const categoryId = $(this).data('id');
            loadProducts(categoryId, 1);
        });

        // Load Cart
        function loadCart() {
            $.ajax({
                url: '{{ route("cart.get") }}',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const cart = response.cart;
                    const total = response.total;

                    let cartHtml = '';
                    Object.entries(cart).forEach(([id, item]) => {
                        cartHtml += `
                        <li class="list-group-item cart-item">
                            <div class="cart-info">
                                <span class="product-name">${item.name}</span>
                                <span class="product-price">₱${(item.price * item.quantity).toFixed(2)}</span>
                            </div>
                            <div class="quantity-controls">
                                <button class="btn btn-sm btn-danger delete-item" data-id="${id}">Delete</button>
                                <button class="btn btn-sm btn-outline-secondary adjust-quantity decrease-quantity" 
                                        data-id="${id}" data-action="decrease" ${item.quantity <= 1 ? 'disabled' : ''}>-</button>
                                <input type="number" 
       class="form-control form-control-sm text-center mx-2 cart-quantity" 
       data-id="${id}" 
       value="${item.quantity}" 
       style="width: 60px;" 
       readonly>

                                <button class="btn btn-sm btn-outline-secondary adjust-quantity increase-quantity" 
                                        data-id="${id}" data-action="increase" ${item.quantity >= item.product_stocks ? 'disabled' : ''}>+</button>
                            </div>
                        </li>`;
                    });

                    $('#cart-items').html(cartHtml);
                    $('#cart-total').text(`₱${total.toFixed(2)}`);

                    // Enable/Disable Checkout Button
                    $('#checkout-btn').prop('disabled', total === 0);
                },
                error: function() {
                    Swal.fire('Error', 'Failed to load cart.', 'error');
                }
            });
        }

        // Clear Cart Button
        $('#clear-cart-btn').on('click', function() {
            $.ajax({
                url: '{{ route("cart.clear") }}',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    loadCart();
                    Swal.fire('Cart Cleared!', 'All items have been removed from the cart.', 'success');
                },
                error: function() {
                    Swal.fire('Error', 'Failed to clear the cart.', 'error');
                }
            });
        });

        // Add to Cart
        $(document).on('click', '.product-card', function() {
            const productId = $(this).data('id');
            $.ajax({
                url: '{{ route("cart.add") }}',
                type: 'POST',
                data: {
                    product_id: productId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    loadCart();
                },
                error: function() {
                    Swal.fire('Error', 'Failed to add product to cart.', 'error');
                }
            });
        });

        // Adjust Quantity (Increase or Decrease)
        $(document).on('click', '.adjust-quantity', function() {
            const productId = $(this).data('id');
            const action = $(this).data('action');
            const input = $(`.cart-quantity[data-id="${productId}"]`);
            let quantity = parseInt(input.val());

            // Get the product's available stock
            let availableStock = 0;
            let notificationShown = false; // Flag to prevent duplicate notification

            $.ajax({
                url: '{{ route("products.checkStock") }}',
                type: 'GET',
                data: {
                    product_id: productId,
                    quantity: quantity
                },
                success: function(response) {
                    availableStock = response.product_stocks;

                    if (action === 'increase' && quantity < availableStock) {
                        quantity++;
                    } else if (action === 'decrease' && quantity > 1) {
                        quantity--;
                    }

                    input.val(quantity);
                    updateCart(productId, quantity);

                    // Disable the increase button if quantity reaches stock limit
                    if (quantity >= availableStock) {
                        $(`.increase-quantity[data-id="${productId}"]`).prop('disabled', true);

                        if (!notificationShown) {
                            // Show the notification only once
                            Swal.fire({
                                icon: 'info',
                                title: 'Max Stock Reached',
                                text: `You can only add up to ${availableStock} items to the cart.`,
                                confirmButtonText: 'OK'
                            });

                            notificationShown = true; // Mark notification as shown
                        }
                    } else {
                        $(`.increase-quantity[data-id="${productId}"]`).prop('disabled', false);
                    }

                    // Enable the decrease button if quantity is greater than 1
                    if (quantity > 1) {
                        $(`.decrease-quantity[data-id="${productId}"]`).prop('disabled', false);
                    } else {
                        $(`.decrease-quantity[data-id="${productId}"]`).prop('disabled', true);
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON.error || 'Failed to check stock.', 'error');
                }
            });
        });


        // Update Cart
        function updateCart(productId, quantity) {
            $.ajax({
                url: '{{ route("cart.update") }}',
                type: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    loadCart(); // Reload cart with updated quantities
                },
                error: function() {
                    Swal.fire('Error', 'Failed to update cart.', 'error');
                }
            });
        }

        // Delete Cart Item
        $(document).on('click', '.delete-item', function() {
            const productId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "This will remove the item from your cart.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("cartDestroyPOS", ":id") }}'.replace(':id', productId),
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            Swal.fire('Deleted!', response.message, 'success');
                            loadCart();
                        },
                        error: function(xhr) {
                            Swal.fire('Error', xhr.responseJSON.message || 'Failed to delete item from cart.', 'error');
                        }
                    });
                }
            });
        });

        // Submit Payment (Checkout)
        $('#checkout-btn').on('click', function() {
            $('#cashPaidModal').modal('show');
        });

        // Submit Payment Handler
        $('#submit-payment').on('click', function() {
            const cashPaid = parseFloat($('#cash-paid').val());
            const totalAmount = parseFloat($('#cart-total').text().replace('₱', '').replace(',', ''));

            if (!cashPaid || isNaN(cashPaid) || cashPaid < totalAmount) {
                Swal.fire('Error', 'Cash paid must be greater than or equal to total amount.', 'error');
                return;
            }

            $.ajax({
                url: '{{ route("checkout") }}',
                type: 'POST',
                data: {
                    cash_paid: cashPaid,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#cashPaidModal').modal('hide');
                    Swal.fire('Success', `Order placed successfully! Change: ₱${response.change.toFixed(2)}`, 'success');
                    loadCart();
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON.message || 'Checkout failed.', 'error');
                }
            });
        });

        // Order History Button Click Handler
        $('#order-history-btn').on('click', function() {
            loadOrderHistory();
            $('#orderHistoryModal').modal('show');
        });

        // Load Order History
        function loadOrderHistory() {
            $('#order-history-table').html('<tr><td colspan="6" class="text-center"><div class="spinner-border text-primary" role="status"></div></td></tr>');
            $('#order-details-section').hide();

            $.ajax({
                url: '{{ route("orders.history") }}',
                type: 'GET',
                success: function(orders) {
                    let html = '';
                    if (orders.length === 0) {
                        html = '<tr><td colspan="6" class="text-center">No orders found</td></tr>';
                    } else {
                        orders.forEach(order => {
                            const date = new Date(order.created_at);
                            const formattedDate = date.toLocaleString();

                            html += `
                <tr>
                    <td>${order.order_number}</td>
                    <td>${formattedDate}</td>
                    <td>₱${parseFloat(order.total_amount).toFixed(2)}</td>
                    <td>₱${parseFloat(order.cash_paid).toFixed(2)}</td>
                    <td>₱${parseFloat(order.change).toFixed(2)}</td>
                    <td>
                        <button class="btn btn-sm btn-primary view-order-details" data-id="${order.id}">
                            View Details
                        </button>
                    </td>
                </tr>`;
                        });
                    }
                    $('#order-history-table').html(html);
                },
                error: function(xhr, status, error) {
                    console.error("Error loading order history:", xhr.responseText);
                    Swal.fire('Error', 'Failed to load order history: ' + error, 'error');
                    $('#order-history-table').html('<tr><td colspan="6" class="text-center">Failed to load order history</td></tr>');
                }
            });
        }
        // Fix for aria-hidden issue with modals
        $('#orderHistoryModal').on('shown.bs.modal', function() {
            // Remove aria-hidden attribute when modal is shown
            $('.dashboard-main-wrapper').removeAttr('aria-hidden');
        });

        // When modal is hidden, restore the attribute if needed
        $('#orderHistoryModal').on('hidden.bs.modal', function() {
            // Only set aria-hidden back if the element is not focused
            if (!$('.dashboard-main-wrapper').find(':focus').length) {
                $('.dashboard-main-wrapper').attr('aria-hidden', 'true');
            }
        });
        // View Order Details Click Handler
        $(document).on('click', '.view-order-details', function() {
            const orderId = $(this).data('id');
            loadOrderDetails(orderId);
        });

        // Load Order Details
        function loadOrderDetails(orderId) {
            $('#order-details-table').html('<tr><td colspan="4" class="text-center"><div class="spinner-border text-primary" role="status"></div></td></tr>');
            $('#order-details-section').show();

            $.ajax({
                url: '{{ route("orders.details", ":id") }}'.replace(':id', orderId),
                type: 'GET',
                success: function(orderDetails) {
                    let html = '';
                    let totalAmount = 0;

                    if (orderDetails.length === 0) {
                        html = '<tr><td colspan="4" class="text-center">No order details found</td></tr>';
                    } else {
                        orderDetails.forEach(item => {
                            const subtotal = parseFloat(item.price) * parseInt(item.quantity);
                            totalAmount += subtotal;

                            html += `
                        <tr>
                            <td>${item.product_name}</td>
                            <td>${item.quantity}</td>
                            <td>₱${parseFloat(item.price).toFixed(2)}</td>
                            <td>₱${subtotal.toFixed(2)}</td>
                        </tr>`;
                        });

                        // Add total amount row
                        html += `
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Total Amount:</td>
                        <td class="fw-bold text-primary">₱${totalAmount.toFixed(2)}</td>
                    </tr>`;
                    }

                    $('#order-details-table').html(html);
                    showPrintButton(orderId);
                },
                error: function() {
                    Swal.fire('Error', 'Failed to load order details.', 'error');
                    $('#order-details-table').html('<tr><td colspan="4" class="text-center">Failed to load order details</td></tr>');
                }
            });
        }

        // Show print button after loading order details
        function showPrintButton(orderId) {
            $('#print-receipt-btn').show().off('click').on('click', function() {
                window.open(`/admin/pos/receipt/${orderId}`, '_blank');
            });
        }

        // Initial Load
        loadCategories();
        loadProducts();
        loadCart();
    });
</script>
@endsection