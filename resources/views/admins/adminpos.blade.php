@extends('admins.layout')
@section('title', 'Mayah Store - Admin POS')
@section('content')
@include('admins.adminheader', ['activePage' => 'pos'])

<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f8f9fa;
    }

    .dashboard-wrapper {
        padding: 20px;
    }

    .category-item:hover {
        background-color: #e9ecef;
    }

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

@section('scripts')
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
                        Swal.fire({
                            icon: 'info',
                            title: 'Max Stock Reached',
                            text: `You can only add up to ${availableStock} items to the cart.`,
                            confirmButtonText: 'OK'
                        });
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

        // Disable manual input, only allow increase/decrease via buttons
        $(document).on('focus', '.cart-quantity', function() {
            // When input is focused, prevent typing
            $(this).blur();
        });

        // Adjust Quantity (Increase or Decrease)
        $(document).on('click', '.adjust-quantity', function() {
            const productId = $(this).data('id');
            const action = $(this).data('action');
            const input = $(`.cart-quantity[data-id="${productId}"]`);
            let quantity = parseInt(input.val());

            // Get the product's available stock
            $.ajax({
                url: '{{ route("products.checkStock") }}',
                type: 'GET',
                data: {
                    product_id: productId,
                    quantity: quantity
                },
                success: function(response) {
                    const availableStock = response.product_stocks;

                    if (action === 'increase' && quantity < availableStock) {
                        quantity++;
                    } else if (action === 'decrease' && quantity > 1) {
                        quantity--;
                    }

                    input.val(quantity); // Update the input with new value
                    updateCart(productId, quantity); // Update the cart

                    // Disable the increase button if quantity reaches stock limit
                    if (quantity >= availableStock) {
                        $(`.increase-quantity[data-id="${productId}"]`).prop('disabled', true);
                        Swal.fire({
                            icon: 'info',
                            title: 'Max Stock Reached',
                            text: `You can only add up to ${availableStock} items to the cart.`,
                            confirmButtonText: 'OK'
                        });
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

        // Initial Load
        loadCategories();
        loadProducts();
        loadCart();
    });
</script>
@endsection
@endsection