@extends('admins.layout')
@section('title', 'Mayah Store - Admin Products')

@include('admins.adminheader', ['activePage' => 'products'])
@section('content')

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
                            <input type="text" class="form-control form-control-sm" placeholder="Search...">
                        </div>

                        <div class="mr-2">
                            <select class="form-control form-control-sm" style="width: 70px;">
                                <option>10</option>
                                <option>20</option>
                                <option>50</option>
                                <option>100</option>
                            </select>
                        </div>

                        <button class="btn btn-sm btn-outline-warning mr-2">
                            <i class="fa fa-filter"></i> Filter
                        </button>

                        <button class="btn btn-sm btn-outline-danger mr-2">
                            <i class="fa fa-file-export"></i> Export
                        </button>

                        <a href="#" class="btn btn-sm btn-warning text-white">
                            <i class="fa fa-plus-circle"></i> Add Product
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
                                        <th class="border-0">Price</th>
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
                                        <td>Product #1 </td>
                                        <td>Biscuits </td>
                                        <td>20</td>
                                        <td>$80.00</td>
                                        <td>Paid</td>
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
                                        <td>Product #2 </td>
                                        <td>Biscuits </td>
                                        <td>12</td>
                                        <td>$180.00</td>
                                        <td>Paid</td>
                                        <td>
                                            <i class="ri-mail-line" style="margin-right: 0.5rem;"></i>
                                            <i class="ri-delete-bin-line"></i>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>3</td>
                                        <td>
                                            <div class="m-r-10">
                                                <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Bread Stix" class="rounded" width="45">
                                            </div>
                                        </td>
                                        <td>Product #3 </td>
                                        <td>Biscuits </td>
                                        <td>23</td>
                                        <td>$820.00</td>
                                        <td>Paid</td>
                                        <td>
                                            <i class="ri-mail-line" style="margin-right: 0.5rem;"></i>
                                            <i class="ri-delete-bin-line"></i>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>4</td>
                                        <td>
                                            <div class="m-r-10">
                                                <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Bread Stix" class="rounded" width="45">
                                            </div>
                                        </td>
                                        <td>Product #4 </td>
                                        <td>Biscuits </td>
                                        <td>34</td>
                                        <td>$340.00</td>
                                        <td>Paid</td>
                                        <td>
                                            <i class="ri-mail-line" style="margin-right: 0.5rem;"></i>
                                            <i class="ri-delete-bin-line"></i>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>5</td>
                                        <td>
                                            <div class="m-r-10">
                                                <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Bread Stix" class="rounded" width="45">
                                            </div>
                                        </td>
                                        <td>Product #5 </td>
                                        <td>Biscuits </td>
                                        <td>34</td>
                                        <td>$340.00</td>
                                        <td>Paid</td>
                                        <td>
                                            <i class="ri-mail-line" style="margin-right: 0.5rem;"></i>
                                            <i class="ri-delete-bin-line"></i>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>6</td>
                                        <td>
                                            <div class="m-r-10">
                                                <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Bread Stix" class="rounded" width="45">
                                            </div>
                                        </td>
                                        <td>Product #6 </td>
                                        <td>Biscuits </td>
                                        <td>34</td>
                                        <td>$340.00</td>
                                        <td>Paid</td>
                                        <td>
                                            <i class="ri-mail-line" style="margin-right: 0.5rem;"></i>
                                            <i class="ri-delete-bin-line"></i>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>7</td>
                                        <td>
                                            <div class="m-r-10">
                                                <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Bread Stix" class="rounded" width="45">
                                            </div>
                                        </td>
                                        <td>Product #7 </td>
                                        <td>Biscuits </td>
                                        <td>34</td>
                                        <td>$340.00</td>
                                        <td>Paid</td>
                                        <td>
                                            <i class="ri-mail-line" style="margin-right: 0.5rem;"></i>
                                            <i class="ri-delete-bin-line"></i>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>8</td>
                                        <td>
                                            <div class="m-r-10">
                                                <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Bread Stix" class="rounded" width="45">
                                            </div>
                                        </td>
                                        <td>Product #8 </td>
                                        <td>Biscuits </td>
                                        <td>34</td>
                                        <td>$340.00</td>
                                        <td>Paid</td>
                                        <td>
                                            <i class="ri-mail-line" style="margin-right: 0.5rem;"></i>
                                            <i class="ri-delete-bin-line"></i>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>9</td>
                                        <td>
                                            <div class="m-r-10">
                                                <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Bread Stix" class="rounded" width="45">
                                            </div>
                                        </td>
                                        <td>Product #9 </td>
                                        <td>Biscuits </td>
                                        <td>34</td>
                                        <td>$340.00</td>
                                        <td>Paid</td>
                                        <td>
                                            <i class="ri-mail-line" style="margin-right: 0.5rem;"></i>
                                            <i class="ri-delete-bin-line"></i>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>10</td>
                                        <td>
                                            <div class="m-r-10">
                                                <img src="{{ asset('assets/img/BISCUITS-1.png') }}" alt="Bread Stix" class="rounded" width="45">
                                            </div>
                                        </td>
                                        <td>Product #10 </td>
                                        <td>Biscuits </td>
                                        <td>34</td>
                                        <td>$340.00</td>
                                        <td>Paid</td>
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