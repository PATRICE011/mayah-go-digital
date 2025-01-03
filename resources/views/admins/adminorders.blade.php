@extends('admins.layout')
@section('title', 'Mayah Store - Admin Products')
@section('content')
@include('admins.adminheader', ['activePage' => 'stocks'])
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

                        <button class="btn btn-sm btn-outline-warning mr-2">
                            <i class="fa fa-filter"></i> Filter
                        </button>

                        <button class="btn btn-sm btn-outline-danger mr-2">
                            <i class="fa fa-file-export"></i> Export
                        </button>

                        <a href="#" class="btn btn-sm btn-warning text-white">
                            <i class="fa fa-plus-circle"></i> Add Category
                        </a>
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
                                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true"> < </a>
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