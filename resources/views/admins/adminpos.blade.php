@extends('admins.layout')
@section('title', 'Mayah Store - Admin POS')
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
                <div id="products" class="row g-4">
                    <!-- Products will be dynamically loaded here -->
                </div>

                <!-- Pagination -->
                <nav id="pagination" class="mt-4 d-flex justify-content-center">
                    <!-- Pagination will be dynamically loaded here -->
                </nav>
            </div>

            <!-- Checkout Section -->
            <div class="col-md-3 bg-white shadow-sm rounded p-3">
                <h5 class="text-primary mb-3">Checkout</h5>
                <ul id="cart-items" class="list-group mb-3">
                    <!-- Cart items will be dynamically added here -->
                </ul>
                <div class="d-flex justify-content-between mb-3">
                    <strong>Total:</strong>
                    <span id="cart-total" class="text-primary font-weight-bold">₱0.00</span>
                </div>
                <button id="checkout-btn" class="btn btn-success w-100 mb-2" disabled>Checkout</button>
            </div>
        </div>
    </div>
</div>

<!-- Cash Paid Modal -->
<div class="modal fade" id="cashPaidModal" tabindex="-1" role="dialog" aria-labelledby="cashPaidModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary" id="cashPaidModalLabel">Enter Payment Details</h5>
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
    $(document).ready(function () {
        const csrfToken = '{{ csrf_token() }}';

        // Load Categories
        function loadCategories() {
            $.ajax({
                url: '{{ route("categories.get") }}',
                type: 'GET',
                success: function (categories) {
                    let html = `
                        <a href="#" data-id="all" class="category-item text-decoration-none">
                            <li class="list-group-item list-group-item-action">Show All</li>
                        </a>`;
                    categories.forEach(category => {
                        html += `
                            <a href="#" data-id="${category.id}" class="category-item text-decoration-none">
                                <li class="list-group-item list-group-item-action">${category.category_name}</li>
                            </a>`;
                    });
                    $('#categories').html(html);
                },
                error: function () {
                    Swal.fire('Error', 'Failed to load categories.', 'error');
                }
            });
        }

        // Load Products
        function loadProducts(categoryId = 'all', page = 1) {
            $('#products').html('<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>');
            $.ajax({
                url: '{{ route("products.get") }}',
                type: 'GET',
                data: { category_id: categoryId, page },
                success: function (response) {
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
                error: function () {
                    Swal.fire('Error', 'Failed to load products.', 'error');
                }
            });
        }

        // Load Cart
        function loadCart() {
            $.ajax({
                url: '{{ route("cart.get") }}',
                type: 'GET',
                success: function (response) {
                    const cart = response.cart;
                    const total = response.total;

                    let cartHtml = '';
                    $.each(cart, function (id, item) {
                        cartHtml += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            ${item.name} 
                            <div class="d-flex align-items-center">
                                <button class="btn btn-sm btn-outline-secondary adjust-quantity" data-id="${id}" data-action="decrease">-</button>
                                <input type="number" class="form-control form-control-sm text-center mx-2 cart-quantity" data-id="${id}" value="${item.quantity}" style="width: 60px;">
                                <button class="btn btn-sm btn-outline-secondary adjust-quantity" data-id="${id}" data-action="increase">+</button>
                                <span class="ms-3">₱${item.subtotal.toFixed(2)}</span>
                            </div>
                        </li>`;
                    });

                    $('#cart-items').html(cartHtml);
                    $('#cart-total').text(`₱${total.toFixed(2)}`);
                    $('#checkout-btn').prop('disabled', total === 0);
                },
                error: function () {
                    Swal.fire('Error', 'Failed to load cart.', 'error');
                }
            });
        }

        // Adjust Quantity
        $(document).on('click', '.adjust-quantity', function () {
            const productId = $(this).data('id');
            const action = $(this).data('action');
            const input = $(`.cart-quantity[data-id="${productId}"]`);
            let quantity = parseInt(input.val());

            if (action === 'increase') {
                quantity++;
            } else if (action === 'decrease' && quantity > 1) {
                quantity--;
            }

            input.val(quantity);
            updateCart(productId, quantity);
        });

        // Update Cart
        function updateCart(productId, quantity) {
            $.ajax({
                url: '{{ route("cart.update") }}',
                type: 'POST',
                data: { product_id: productId, quantity, _token: csrfToken },
                success: function () {
                    loadCart();
                },
                error: function () {
                    Swal.fire('Error', 'Failed to update cart.', 'error');
                }
            });
        }

        // Add to Cart
        $(document).on('click', '.product-card', function () {
            const productId = $(this).data('id');
            $.ajax({
                url: '{{ route("cart.add") }}',
                type: 'POST',
                data: { product_id: productId, _token: csrfToken },
                success: function () {
                    loadCart();
                },
                error: function () {
                    Swal.fire('Error', 'Failed to add product to cart.', 'error');
                }
            });
        });

        // Filter Products by Category
        $(document).on('click', '.category-item', function (e) {
            e.preventDefault();
            const categoryId = $(this).data('id');
            loadProducts(categoryId);
        });

        // Pagination
        $(document).on('click', '.pagination-item', function (e) {
            e.preventDefault();
            const page = $(this).data('page');
            loadProducts('all', page);
        });

        // Checkout
        $('#checkout-btn').on('click', function () {
            $('#cashPaidModal').modal('show');
        });

        $('#submit-payment').on('click', function () {
            const cashPaid = parseFloat($('#cash-paid').val());
            const totalAmount = parseFloat($('#cart-total').text().replace('₱', '').replace(',', ''));

            if (!cashPaid || isNaN(cashPaid) || cashPaid < totalAmount) {
                Swal.fire('Error', 'Cash paid must be greater than or equal to total amount.', 'error');
                return;
            }

            $.ajax({
                url: '{{ route("checkout") }}',
                type: 'POST',
                data: { cash_paid: cashPaid, _token: csrfToken },
                success: function (response) {
                    $('#cashPaidModal').modal('hide');
                    Swal.fire('Success', `Order placed successfully! Change: ₱${response.change.toFixed(2)}`, 'success');
                    loadCart();
                },
                error: function (xhr) {
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
