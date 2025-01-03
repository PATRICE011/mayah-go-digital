@extends('admins.layout')
@section('title', 'Mayah Store - Admin Customers')
@section('content')
@include('admins.adminheader', ['activePage' => 'customers'])

<div class="dashboard-wrapper">
    <div class="container-fluid  dashboard-content">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h3 class="mb-2">Customers</h3>

                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#" class="breadcrumb-link">Dashboard</a>
                                </li>

                                <li class="breadcrumb-item">
                                    <a href="#" class="breadcrumb-link">Users</a>
                                </li>

                                <li class="breadcrumb-item">
                                    <a href="#" class="breadcrumb-link">Customers</a>
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
                                        <h5 class="modal-title" id="filterModalLabel">Filter Customers</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Add Filter Fields Here -->
                                        <form id="filterForm">
                                            <div class="form-group">
                                                <label for="filterName">Name</label>
                                                <input type="text" class="form-control" id="filterName" placeholder="Enter name">
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
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr class="border-0">
                                        <th class="border-0">#</th>
                                        <th class="border-0">Name</th>
                                        <th class="border-0">Phone Number</th>
                                        <th class="border-0">Status</th>
                                        <th class="border-0">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>John Doe</td>
                                        <td>0912 345 6789</td>
                                        <td>Active</td>
                                        <td>
                                            <div class="action__btn">
                                                <!-- EDIT BUTTON -->
                                                <button class="edit" data-toggle="modal" data-target="#editModal">
                                                    <i class="ri-mail-line"></i>
                                                </button>

                                                <!-- EDIT MODAL -->
                                                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel">Edit Customer Info</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <form id="editForm">
                                                                    <div class="form-group">
                                                                        <label for="editCustomerName">Customer Name</label>
                                                                        <input type="text" class="form-control" id="editCustomerName" placeholder="Enter Customer name">
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label for="editPhoneNumber">Phone Number</label>
                                                                        <input type="tel" class="form-control" id="editPhoneNumber" placeholder="Enter phone number" pattern="[0-9]+" minlength="10" maxlength="15">
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
                                                                <button type="button" class="btn btn-primary" onclick="applyFilters()">Apply Changes</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- ARCHIVE BUTTON -->
                                                <button class="archive" data-bs-toggle="modal" data-bs-target="#archiveModal">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>

                                                <!-- ARCHIVE MODAL -->
                                                <div class="modal fade" id="archiveModal" tabindex="-1" aria-labelledby="archiveModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="archiveModalLabel">Archive Item</h5>
                                                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to archive this item? This action cannot be undone.
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="button" class="btn btn-danger">Archive</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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