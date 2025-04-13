@extends('home.layout')

@section('title', 'Mayah Store - Receipt')
@section('styles')
<style>
    @media print {
        @page {
            size: auto;
            margin: 10mm;
            /* Reduce default margin */
        }

        body {
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .invoice-container {
            padding: 10px;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td,
        table th {
            padding: 5px 8px;
            font-size: 11px;
        }

        .invoice-head-top img {
            height: 40px;
        }

        .invoice-head,
        .invoice-body,
        .invoice-foot {
            padding: 0;
            margin: 0;
        }

        .invoice-body-bottom {
            margin-top: 10px;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-start {
            text-align: left;
        }

        .invoice-head-bottom ul,
        .invoice-head-middle {
            margin: 0;
        }

        .invoice-head-bottom ul li {
            font-size: 11px;
        }

        .hr {
            border-top: 1px solid #ccc;
            margin: 5px 0;
        }

        footer,
        header,
        nav,
        .no-print {
            display: none !important;
        }
    }
</style>

@endsection
@section('content')
<div class="invoice-wrapper" id="print-area">
    <div class="invoice">
        <div class="invoice-container">
            <!-- Header -->
            <div class="invoice-head">
                <div class="invoice-head-top">
                    <div class="invoice-head-top-left text-start">
                        <img src="{{ asset('assets/img/MAYAH-STORE-LOGO.jpg') }}" alt="Logo" style="height: 60px;">
                    </div>
                    <div class="invoice-head-top-right text-end">
                        <h3>Receipt</h3>
                    </div>
                </div>

                <div class="hr"></div>

                <!-- Date & Invoice No -->
                <div class="invoice-head-middle">
                    <div class="invoice-head-middle-left text-start">
                        <p><span class="text-bold">Date:</span> {{ \Carbon\Carbon::parse($order->created_at)->format('F j, Y g:i A') }}</p>
                    </div>
                    <div class="invoice-head-middle-right text-end">
                        <p><span class="text-bold">Receipt No:</span> {{ $order->order_number }}</p>
                    </div>
                </div>

                <div class="hr"></div>

                <!-- Invoiced To & Pay To -->
                <div class="invoice-head-bottom">
                    <div class="invoice-head-bottom-left">
                        <ul>
                            <li class="text-bold">Invoiced To:</li>
                            <li>Walk-in Customer</li>
                            <li>-</li>
                            <li>-</li>
                        </ul>
                    </div>
                    <div class="invoice-head-bottom-right">
                        <ul class="text-end">
                            <li class="text-bold">Pay To:</li>
                            <li>Mayah Store</li>
                            <li>Gen. T. De Leon, Valenzuela City</li>
                            <li>mayahstore@example.com</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Body -->
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
                            @foreach ($items as $item)
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

                    <!-- Totals -->
                    <div class="invoice-body-bottom">
                        <div class="invoice-body-info-item">
                            <div class="info-item-td text-end text-bold">Total:</div>
                            <div class="info-item-td text-end">₱{{ number_format($order->cash_paid - $order->change, 2) }}</div>
                        </div>
                        <div class="invoice-body-info-item">
                            <div class="info-item-td text-end text-bold">Cash Paid:</div>
                            <div class="info-item-td text-end">₱{{ number_format($order->cash_paid, 2) }}</div>
                        </div>
                        <div class="invoice-body-info-item">
                            <div class="info-item-td text-end text-bold">Change:</div>
                            <div class="info-item-td text-end">₱{{ number_format($order->change, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="invoice-foot text-center mt-4">
                <p>
                    <span class="text-bold">NOTE:</span> This is a computer-generated receipt and does not require a physical signature.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    
    window.addEventListener('load', () => {
        document.querySelectorAll('p').forEach(p => adjustTextSize(p));

        function adjustTextSize(element) {
            let fontSize = parseFloat(window.getComputedStyle(element).fontSize);
            while (element.scrollWidth > element.offsetWidth && fontSize > 8) {
                fontSize -= 0.5;
                element.style.fontSize = `${fontSize}px`;
            }
        }

        window.print();
        setTimeout(() => window.close(), 700);
    });
</script>
@endsection