@extends('admins.layout')
@section('title', 'Mayah Store - Admin Products')
@section('content')
@include('admins.adminheader', ['activePage' => 'categories'])

<div class="dashboard-wrapper">
    <div class="container-fluid  dashboard-content">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h3 class="mb-2">Category</h3>

                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#" class="breadcrumb-link">Dashboard</a>
                                </li>

                                <li class="breadcrumb-item">
                                    <a href="#" class="breadcrumb-link">Category</a>
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
                            <input type="text" id="searchCategory" class="form-control form-control-sm" placeholder="Search...">
                        </div>

                        <button class="btn btn-sm btn-outline-danger mr-2" id="exportCategoryBtn">
                            <i class="fa fa-file-export"></i> Export
                        </button>

                        <!-- ADD CATEGORY BUTTON -->
                        <button class="btn btn-sm btn-warning text-white" data-toggle="modal" data-target="#addModal">
                            <i class="fa fa-plus-circle"></i> Add Category
                        </button>

                        <!-- ADD MODAL -->
                        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addModalLabel">Add Category</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <form id="addForm">
                                            @csrf

                                            <div class="form-group">
                                                <label for="addImage">Category Image</label>
                                                <input type="file" name="category_image" class="form-control" id="addImage" accept="image/*">
                                                <small class="form-text text-muted">Choose an image file to upload (e.g., JPG, PNG).</small>

                                                <div class="mt-3">
                                                    <img id="imagePreview" src="" alt="Selected Image" style="max-width: 150px; display: none; border: 1px solid #ddd; padding: 5px;">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="addCategoryName">Category Name</label>
                                                <input name="category_name" type="text" class="form-control" id="addCategoryName" placeholder="Enter category name">
                                            </div>

                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Add Category</button>
                                            </div>
                                        </form>
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
                                        <th class="border-0">Category</th>

                                        <th class="border-0">Action</th>
                                    </tr>
                                </thead>

                                <tbody id="categoryTableBody">
                                    <!-- AJAX will populate this dynamically -->

                                    <!-- Pagination Row (AJAX will update this) -->
                                    <tr id="paginationRow">
                                        <td colspan="8" class="text-right">
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination justify-content-end mb-0">
                                                    <!-- Pagination will be inserted dynamically here -->
                                                </ul>
                                            </nav>
                                        </td>
                                    </tr>

                                </tbody>

                                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form id="editForm">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">Edit Category</h5>
                                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="editCategoryName">Category Name</label>
                                                        <input type="text" class="form-control" id="editCategoryName" name="category_name" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="editImage">Category Image</label>
                                                        <input type="file" class="form-control" id="editImage" name="category_image" accept="image/*">
                                                        <img id="editImagePreview" src="" alt="Preview" class="mt-2" style="display: none; max-height: 100px;">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

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

                            </table>
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
<script src="{{ asset('assets/js/category.js')  }}?v={{ time() }}"></script>
@endsection
@endsection