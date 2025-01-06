@extends('admins.layout')
@section('title', 'Mayah Store - Admin Products')
@section('content')
@include('admins.adminheader', ['activePage' => 'products'])
<div class="dashboard-wrapper">
    <div class="container-fluid  dashboard-content">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
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
                                            <div class="form-group">
                                                <label for="addStatus">Status</label>
                                                <select class="form-control" id="addStatus">
                                                    <option value="active">Active</option>
                                                    <option value="inactive">Inactive</option>
                                                </select>
                                            </div>
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
<<<<<<< HEAD
                                    <!-- DYNAMIC PRODUCTS SECTION -->
=======
                                    <!-- DYNAMIC -->
>>>>>>> 47b6fc53993aa566442cf3f8e5c38db81568e256
                                </tbody>
                            </table>
                            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <form id="editForm">
                                                <input type="hidden" id="editId"> <!-- Store Product ID -->

                                                <!-- Store Current Image Path -->
                                                <input type="hidden" id="editCurrentImage" name="editCurrentImage">

                                                <div class="form-group">
                                                    <label for="editImage">Product Image</label>
                                                    <input type="file" class="form-control" id="editImage" accept="image/*">
                                                    <small class="form-text text-muted">Choose an image file to upload (e.g., JPG, PNG).</small>

                                                    <div class="mt-3">
                                                    <img id="imagePreview" src="" alt="Selected Image" style="max-width: 150px; display: none; border: 1px solid #ddd; padding: 5px;">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="editName">Product Name</label>
                                                    <input type="text" class="form-control" id="editName" placeholder="Enter product name">
                                                </div>

                                                <div class="form-group">
                                                    <label for="editDescription">Product Description</label>
                                                    <textarea class="form-control" id="editDescription" rows="5" placeholder="Enter product description"></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label for="editCategory">Category</label>
                                                    <select class="form-control" id="editCategory">
                                                        <option value="1">Biscuits</option>
                                                        <option value="2">Drinks</option>
                                                        <option value="3">School Supplies</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="editPrice">Price</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">₱</span>
                                                        <input type="number" class="form-control" id="editPrice" placeholder="Enter price" min="0" step="0.01">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="editStatus">Status</label>
                                                    <select class="form-control" id="editStatus">
                                                        <option value="active">Active</option>
                                                        <option value="inactive">Inactive</option>
                                                    </select>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" onclick="updateProduct()">Save Changes</button>
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
@endsection
@endsection