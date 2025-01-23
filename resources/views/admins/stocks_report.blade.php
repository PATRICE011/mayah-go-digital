@extends('admins.layout')
@section('title', 'Mayah Store - Stocks Report')
@section('content')
@include('admins.adminheader', ['activePage' => 'stocksreport'])

<div class="dashboard-wrapper">
    <div class="container-fluid  dashboard-content">
        <!-- Page Header -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h3 class="mb-2">Stock In-Out Balance Tracker</h3>
                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#" class="breadcrumb-link">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="#" class="breadcrumb-link">Reports</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Stock In-Out Balance Tracker
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card with Table -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-end align-items-center">
                        <div class="mr-2" style="width: 200px;">
                            <input type="text" class="form-control form-control-sm" placeholder="Search...">
                        </div>
                        <button id="printReportBtn" class="btn btn-sm btn-outline-danger mr-2">
                            <i class="fa fa-file-export"></i> Export
                        </button>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <!-- Stock In-Out Balance Table -->
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th colspan="3" class="text-center bg-success text-white">Stock In</th>
                                        <th colspan="3" class="text-center bg-danger text-white">Stock Out</th>
                                        <th colspan="2" class="text-center bg-primary text-white">Stock Balance</th>
                                    </tr>
                                    <tr>
                                        <!-- Stock In Columns -->
                                        <th>Date</th>
                                        <th>Item Name</th>
                                        <th>In Quantity</th>
                                        <!-- Stock Out Columns -->
                                        <th>Date</th>
                                        <th>Item Name</th>
                                        <th>Out Quantity</th>
                                        <!-- Stock Balance Columns -->
                                        <th>Item Name</th>
                                        <th>Balance Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Dynamic Rows -->
                                    <tr>
                                        <!-- Stock In -->
                                        <td>7/1/2024</td>
                                        <td>Television</td>
                                        <td>20</td>
                                        <!-- Stock Out -->
                                        <td>7/1/2024</td>
                                        <td>Television</td>
                                        <td>4</td>
                                        <!-- Stock Balance -->
                                        <td>Television</td>
                                        <td>16</td>
                                    </tr>
                                    <tr>
                                        <td>7/2/2024</td>
                                        <td>Refrigerator</td>
                                        <td>20</td>
                                        <td>7/2/2024</td>
                                        <td>Refrigerator</td>
                                        <td>3</td>
                                        <td>Refrigerator</td>
                                        <td>17</td>
                                    </tr>
                                    <tr>
                                        <td>7/2/2024</td>
                                        <td>Fan</td>
                                        <td>16</td>
                                        <td>7/2/2024</td>
                                        <td>Fan</td>
                                        <td>4</td>
                                        <td>Fan</td>
                                        <td>12</td>
                                    </tr>
                                    <tr>
                                        <td>7/2/2024</td>
                                        <td>Heater</td>
                                        <td>10</td>
                                        <td>7/2/2024</td>
                                        <td>Heater</td>
                                        <td>10</td>
                                        <td>Heater</td>
                                        <td>0</td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- End of Table -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
