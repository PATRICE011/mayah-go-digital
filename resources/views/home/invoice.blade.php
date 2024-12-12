@extends('home.layout')
@section('title','Mayah Store - Invoice')

<div class="invoice-wrapper" id="print-area">
    <div class="invoice">
        <div class="invoice-container">
            <div class="invoice-head">
                <div class="invoice-head-top">
                    <div class="invoice-head-top-left text-start">
                        <img src="{{ asset('assets/img/MAYAH-STORE-LOGO.jpg') }}">
                    </div>

                    <div class="invoice-head-top-right text-end">
                        <h3>Invoice</h3>
                    </div>
                </div>

                <div class="hr"></div>
                <div class="invoice-head-middle">
                    <div class="invoice-head-middle-left text-start">
                        <p><span class="text-bold">Date</span>: {{ $order->first()->order_date }}</p>
                    </div>

                    <div class="invoice-head-middle-right text-end">
                        <p><span class="text-bold">Invoice No:</span> {{ $order->first()->invoice_number }}</p>
                    </div>
                </div>

                <div class="hr"></div>
                <div class="invoice-head-bottom">
                    <div class="invoice-head-bottom-left">
                        <ul>
                            <li class="text-bold">Invoiced To:</li>
                            <li>{{ $customer->name }}</li>
                            <li>{{ $customer->mobile }}</li>
                        </ul>
                    </div>

                    <!-- Static Pay To Section -->
                    <div class="invoice-head-bottom-right">
                        <ul class="text-end">
                            <li class="text-bold">Pay To:</li>
                            <li>Mayah Store</li>
                            <li>Gen. T. De Leon, Valenzuela City</li>
                            <li>mayastore@example.com</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="overflow-view">
                <div class="invoice-body">
                    <table>
                        <thead>
                            <tr>
                                <td class="text-bold">Product Name</td>
                                <td class="text-bold">Category</td>
                                <td class="text-bold">Price</td>
                                <td class="text-bold">Quantity</td>
                                <td class="text-bold">Amount</td>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($order as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->category_name }}</td> 
                                    <td>₱{{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-end">₱{{ number_format($item->amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="invoice-body-bottom">
                        <div class="invoice-body-info-item">
                            <div class="info-item-td text-end text-bold">Total:</div>
                            <div class="info-item-td text-end">₱{{ number_format($order->first()->total_amount, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="invoice-foot text-center">
                <p>
                    <span class="text-bold text-center">NOTE:&nbsp;</span>This is a computer-generated receipt and does not require a physical signature.
                </p>
            </div>
        </div>
    </div>
</div>
