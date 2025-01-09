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
                            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search...">
                        </div>

                        <button class="btn btn-sm btn-outline-danger mr-2 btn-export">
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

                                        <th class="border-0">Action</th>
                                    </tr>
                                </thead>

                                <tbody id="employeeTableBody">



                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6">
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination justify-content-end mb-0" id="paginationContainer">
                                                    <!-- Pagination Links will be dynamically inserted here -->
                                                </ul>
                                            </nav>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
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
                                                    <input type="text" name="name" class="form-control" id="editEmployeeName" placeholder="Enter Customer name">
                                                </div>

                                                <div class="form-group">
                                                    <label for="editPhoneNumber">Phone Number</label>
                                                    <input type="tel" name="mobile" class="form-control" id="editPhoneNumber" placeholder="Enter phone number" pattern="[0-9]+" minlength="10" maxlength="15">
                                                </div>

                                                <!-- <div class="form-group">
                                                    <label for="editStatus">Status</label>
                                                    <select class="form-control" id="editStatus">
                                                        <option value="active">Active</option>
                                                        <option value="inactive">Inactive</option>
                                                    </select>
                                                </div> -->
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" id="applyChangesButton">Apply Changes</button>
                                        </div>
                                    </div>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('scripts')

<script src="{{ asset('assets/js/customers.js')  }}?v={{ time() }}"></script>
@endsection
@endsection