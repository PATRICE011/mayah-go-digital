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

    .input-group {
        margin-bottom: 20px;
    }

    .pagination {
        margin-top: 20px;
    }

    .pagination-item {
        margin: 0 5px;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    .checkout-section {
        display: flex;
        flex-direction: column;
        padding: 15px;
        max-height: 400px; /* Added fixed height */
        overflow-y: auto; /* Make the checkout section scrollable */
    }

    .cart-item {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 15px;
        border-bottom: 1px solid #ddd;
        margin-bottom: 10px;
        border-radius: 8px;
    }

    .cart-item .cart-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .cart-item .quantity-controls {
        display: flex;
        align-items: center;
        margin-top: 10px;
        justify-content: center;
    }

    .cart-item .delete-item {
        margin-top: 10px;
        align-self: flex-end;
    }

    .total-label {
        font-weight: bold;
        font-size: 1.2rem;
    }

    .checkout-btn {
        margin-top: 20px;
    }

    /* Style for product name and price */
    .product-name {
        font-weight: bold;
        font-size: 1.2rem;
    }

    .product-price {
        font-size: 1.1rem;
        color: #28a745;
        margin-top: 10px;
    }

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
                <div class="d-flex justify-content-between mb-3">
                    <span class="total-label">Total:</span>
                    <span id="cart-total" class="text-primary font-weight-bold">₱0.00</span>
                </div>
                <button id="checkout-btn" class="btn btn-success w-100 mb-2" disabled>Checkout</button>
                <button id="clear-cart-btn" class="btn btn-danger w-100 mb-2">Clear Cart</button>
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

        // Load Products with Search functionality
        function loadProducts(categoryId = 'all', page = 1, searchQuery = '') {
            $('#products').html('<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>');
            $.ajax({
                url: '{{ route("products.search") }}',
                type: 'GET',
                data: { category_id: categoryId, page: page, search: searchQuery },
                success: function(response) {
                    const products = response.products;
                    const pagination = response.pagination;

                    let html = '';
                    products.forEach(product => {
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
                    });
                    $('#products').html(html);

                    let paginationHtml = '';
                    if (pagination.last_page > 1) {
                        for (let i = 1; i <= pagination.last_page; i++) {
                            paginationHtml += `<a href="#" data-page="${i}" class="pagination-item btn btn-sm ${i === pagination.current_page ? 'btn-primary' : 'btn-outline-primary'}">${i}</a>`;
                        }
                    }
                    $('#pagination').html(paginationHtml);
                },
                error: function() {
                    Swal.fire('Error', 'Failed to load products.', 'error');
                }
            });
        }

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
                                <button class="btn btn-sm btn-outline-secondary adjust-quantity" data-id="${id}" data-action="decrease">-</button>
                                <input type="number" class="form-control form-control-sm text-center mx-2 cart-quantity" data-id="${id}" value="${item.quantity}" style="width: 60px;">
                                <button class="btn btn-sm btn-outline-secondary adjust-quantity" data-id="${id}" data-action="increase">+</button>
                            </div>
                        </li>`;
                    });

                    $('#cart-items').html(cartHtml);
                    $('#cart-total').text(`₱${total.toFixed(2)}`);
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

        // Initial Load
        loadCategories();
        loadProducts();
        loadCart();
    });
</script>
@endsection
@endsection
