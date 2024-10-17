@extends('admins.layout')
@section('title')
@section('content')
<main class="container section">
    <div id="preloader" style="display: none;">
        <div class="spinner"></div>
    </div>
    
<div class="orders-container">
        <div class="header">
            <h2>Online Orders</h2>
            <div class="action-buttons">
                <button class="filter-btn">Filter</button>
                <button class="export-btn">Export</button>
            </div>
        </div>

        <table class="orders-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Order Type</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2908243</td>
                    <td><span class="label delivery">Delivery</span></td>
                    <td>Will Smith</td>
                    <td>278.00</td>
                    <td>12:47 AM, 30-08-2024</td>
                    <td><span class="status rejected">Rejected</span></td>
                    <td>
                        <button class="action-btn">
                            <a href="{{ route('admins.view') }}">                            
                                👁️
                            </a>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>2908242</td>
                    <td><span class="label delivery">Delivery</span></td>
                    <td>Will Smith</td>
                    <td>720.00</td>
                    <td>12:47 AM, 30-08-2024</td>
                    <td><span class="status delivered">Delivered</span></td>
                    <td><button class="action-btn">👁️</button></td>
                </tr>
                <tr>
                    <td>2908241</td>
                    <td><span class="label delivery">Delivery</span></td>
                    <td>Will Smith</td>
                    <td>415.20</td>
                    <td>12:47 AM, 30-08-2024</td>
                    <td><span class="status delivered">Delivered</span></td>
                    <td><button class="action-btn">👁️</button></td>
                </tr>
            </tbody>
        </table>

        <p class="footer">Showing 1 to 3 of 3 entries</p>
    </div>
</main>
@endsection

