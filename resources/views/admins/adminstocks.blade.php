@extends('admins.layout')
@section('title', 'Mayah Store - Admin Products')

@include('admins.adminheader', ['activePage' => 'stocks'])
@section('content')

<div class="dashboard-wrapper">
    <div class="container-fluid  dashboard-content">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h3 class="mb-2">Stocks</h3>

                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#" class="breadcrumb-link">Dashboard</a>
                                </li>

                                <li class="breadcrumb-item">
                                    <a href="#" class="breadcrumb-link">Stocks</a>
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
                                        <h5 class="modal-title" id="filterModalLabel">Filter Products</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Add Filter Fields Here -->
                                        <form id="filterForm">
                                            <div class="form-group">
                                                <label for="filterName">Product Name</label>
                                                <input type="text" class="form-control" id="filterName" placeholder="Enter name">
                                            </div>

                                            <div class="form-group">
                                                <label for="filteCategory">Category</label>
                                                <select class="form-control" id="filterCategory">
                                                    <option value="">Biscuits</option>
                                                    <option value="school-supplies">School Supplies</option>
                                                    <option value="drinks">Drinks</option>
                                                </select>
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
                                        <button type="button" class="btn btn-primary" onclick="applyFilters()">Apply Filters</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-sm btn-outline-danger mr-2">
                            <i class="fa fa-file-export"></i> Export
                        </button>

                        <button class="btn btn-sm btn-warning text-white" data-toggle="modal" data-target="#addModal">
                            <i class="fa fa-plus-circle"></i> Add Stocks
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
                                            <div class="form-group">
                                                <label for="addImage">Product Image</label>
                                                <input type="file" class="form-control" id="addImage" accept="image/*">
                                                <small class="form-text text-muted">Choose an image file to upload (e.g., JPG, PNG).</small>

                                                <div class="mt-3">
                                                    <img id="imagePreview" src="" alt="Selected Image" style="max-width: 150px; display: none; border: 1px solid #ddd; padding: 5px;">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="addName">Product Name</label>
                                                <input type="text" class="form-control" id="addName" placeholder="Enter product name">
                                            </div>

                                            <div class="form-group">
                                                <label for="addDescription">Product Description</label>
                                                <textarea class="form-control" id="addDescription" rows="5" placeholder="Enter product description"></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label for="addCategory">Category</label>
                                                <select class="form-control" id="addCategory">
                                                    <option value="">Biscuits</option>
                                                    <option value="">Drinks</option>
                                                    <option value="">School Supplies</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="addPrice">Price</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">₱</span>
                                                    <input type="number" class="form-control" id="addPrice" placeholder="Enter price" min="0" step="0.01">
                                                </div>
                                            </div>

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
                                        <button type="button" class="btn btn-primary" onclick="applyFilters()">Add Product</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr class="border-0">
                                        <th class="border-0">#</th>
                                        <th class="border-0">Image</th>
                                        <th class="border-0">Product Name</th>
                                        <th class="border-0">Category</th>
                                        <th class="border-0">Stocks</th>
                                        <th class="border-0">Status</th>
                                        <th class="border-0">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            <div class="m-r-10">
                                                <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Bread Stix" class="rounded" width="45">
                                            </div>
                                        </td>
                                        <td>Bread Sticks</td>
                                        <td>Biscuits</td>
                                        <td>20</td>
                                        <td>Active</td>
                                        <td>
                                            <i class="ri-mail-line" style="margin-right: 0.5rem;"></i>
                                            <i class="ri-delete-bin-line"></i>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>2</td>
                                        <td>
                                            <div class="m-r-10">
                                                <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Bread Stix" class="rounded" width="45">
                                            </div>
                                        </td>
                                        <td>Bread Sticks</td>
                                        <td>Biscuits</td>
                                        <td>0</td>
                                        <td>Inactive</td>
                                        <td>
                                            <i class="ri-mail-line" style="margin-right: 0.5rem;"></i>
                                            <i class="ri-delete-bin-line"></i>
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