@extends('admins.layout')
@section('title', 'Mayah Store - Admin Products')
@section('content')
@include('admins.adminheader', ['activePage' => 'products'])
<div class="dashboard-wrapper">
    <div class="container-fluid  dashboard-content">

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <h3 class="mb-2">Products</h3>

                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#" class="breadcrumb-link">Dashboard</a>
                                </li>

                                <li class="breadcrumb-item">
                                    <a href="#" class="breadcrumb-link">Products</a>
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
                            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search...">
                        </div>

                        <button class="btn btn-sm btn-outline-warning mr-2" data-toggle="modal" data-target="#filterModal">
                            <i class="fa fa-filter"></i> Filter
                        </button>

                        <!-- Filter Modal -->
                        <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="filterModalLabel">Filter Products</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Add Filter Fields Here -->
                                        <form id="filterForm">

                                            <div class="form-group">
                                                <label for="filterCategory">Category</label>
                                                <select class="form-control" id="filterCategory">
                                                    <option value="">All Categories</option>
                                                    @foreach($categories as $category)
                                                    <option value="{{ $category->slug }}">{{ $category->category_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <div class="form-group">
                                                <label for="filterPrice">Price Range</label>
                                                <div class="row g-2">
                                                    <!-- Min Price -->
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">₱</span>
                                                            <input type="number" class="form-control" id="minPrice" placeholder="Min Price" min="0" step="0.01">
                                                        </div>
                                                    </div>


                                                    <!-- Max Price -->
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text">₱</span>
                                                            <input type="number" class="form-control" id="maxPrice" placeholder="Max Price" min="0" step="0.01">
                                                        </div>
                                                    </div>
                                                </div>
                                                <small class="form-text text-muted">Enter a price range (e.g., ₱100 to ₱1000).</small>
                                            </div>


                                            <div class="form-group">
                                                <label for="filterStatus">Status</label>
                                                <select class="form-control" id="filterStatus">
                                                    <option value="">All</option>
                                                    <option value="active">Active</option>
                                                    <option value="inactive">Inactive</option>
                                                </select>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" id="applyFiltersBtn" class="btn btn-primary">Apply Filters</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-sm btn-outline-danger mr-2" id="exportProductsBtn">
                            <i class="fa fa-file-export"></i> Export
                        </button>


                        <button class="btn btn-sm btn-warning text-white" data-toggle="modal" data-target="#addModal">
                            <i class="fa fa-plus-circle"></i> Add Product
                        </button>

                        <!-- ADD MODAL -->
                        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addModalLabel">Add Product</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <form id="addForm">
                                            <!-- Product Image -->
                                            <div class="form-group">
                                                <label for="addImage">Product Image</label>
                                                <input type="file" class="form-control" id="addImage" accept="image/*">
                                                <small class="form-text text-muted">Choose an image file to upload (e.g., JPG, PNG).</small>

                                            </div>

                                            <!-- Product Name -->
                                            <div class="form-group">
                                                <label for="addName">Product Name</label>
                                                <input type="text" class="form-control" id="addName" placeholder="Enter product name">
                                            </div>

                                            <!-- Product Description -->
                                            <div class="form-group">
                                                <label for="addDescription">Product Description</label>
                                                <textarea class="form-control" id="addDescription" name="product_description" rows="5" placeholder="Enter product description"></textarea>
                                            </div>


                                            <!-- Category -->
                                            <div class="form-group">
                                                <label for="addCategory">Category</label>
                                                <select class="form-control" id="addCategory">
                                                    <!-- Dynamically populated categories -->
                                                </select>
                                            </div>

                                            <!-- Price -->
                                            <div class="form-group">
                                                <label for="addPrice">Price</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">₱</span>
                                                    <input type="number" class="form-control" id="addPrice" placeholder="Enter price" min="0" step="0.01">
                                                </div>
                                            </div>

                                            <!-- Stock -->
                                            <div class="form-group">
                                                <label for="addStocks">Stocks</label>
                                                <input type="number" class="form-control" id="addStocks" placeholder="Enter stock quantity" min="0">
                                            </div>

                                            <!-- Status -->
                                            <!-- <div class="form-group">
                                                <label for="addStatus">Status</label>
                                                <select class="form-control" id="addStatus">
                                                    <option value="active">Active</option>
                                                    <option value="inactive">Inactive</option>
                                                </select>
                                            </div> -->
                                        </form>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" id="addProductBtn" class="btn btn-primary">Add Product</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end of add modal -->
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table" id="productTable">
                                <thead class="bg-light">
                                    <tr class="border-0">
                                        <th class="border-0">#</th>
                                        <th class="border-0">Image</th>
                                        <th class="border-0">Product Name</th>
                                        <th class="border-0">Product Description</th>
                                        <th class="border-0">Category</th>
                                        <th class="border-0">Price</th>
                                        <th class="border-0">Stocks</th>
                                        <th class="border-0">Status</th>
                                        <th class="border-0">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <!-- DYNAMIC -->
                                </tbody>
                            </table>
                            <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="editProductForm" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" id="editProductId" name="id">

                                                <div class="form-group">
                                                    <label for="editProductName">Product Name</label>
                                                    <input type="text" class="form-control" id="editProductName" name="product_name" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="editProductDescription">Product Description</label>
                                                    <textarea class="form-control" id="editProductDescription" name="product_description" rows="4"></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label for="editCategory">Category</label>
                                                    <select class="form-control" id="editCategory" name="category_id" required>
                                                        <!-- Dynamically populate categories here -->
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="editProductPrice">Price</label>
                                                    <input type="number" class="form-control" id="editProductPrice" name="product_price" required min="0" step="0.01">
                                                </div>

                                                <div class="form-group">
                                                    <label for="editProductStocks">Stocks</label>
                                                    <input type="number" class="form-control" id="editProductStocks" name="product_stocks" required min="0">
                                                </div>

                                                <div class="form-group">
                                                    <label for="editProductImage">Product Image</label>
                                                    <input type="file" class="form-control" id="editProductImage" name="product_image" accept="image/*">
                                                    <img id="currentImagePreview" src="" alt="Current Image" style="max-width: 100px; display: none;">
                                                </div>


                                            </form>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" id="editProductBtn" class="btn btn-primary">save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- modal -->
                              <!-- ARCHIVE MODAL -->
                              <div class="modal fade" id="archiveModal" tabindex="-1" aria-labelledby="archiveModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="archiveModalLabel">Archive Item</h5>
                                                <!-- <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button> -->
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


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('scripts')
<script>
    const baseURL = "{{ asset('assets/img/') }}";
</script>
<script src="{{ asset('assets/js/product.js')  }}?v={{ time() }}"></script>

@endsection
@endsection