<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <h2>Sales Report</h2>
    @if ($search)
        <p>Search Term: <strong>{{ $search }}</strong></p>
    @endif
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Amount</th>
                <th>Date</th>
                <th>Customer</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $index => $sale)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $sale->product_name }}</td>
                    <td>{{ $sale->quantity }}</td>
                    <td>{{ number_format($sale->unit_price, 2) }}</td>
                    <td>{{ number_format($sale->total_amount, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($sale->date)->format('h:i A, d-m-Y') }}</td>
                    <td>{{ $sale->customer_name ?? 'Guest' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        window.print(); // Automatically open the print dialog
    </script>
</body>
</html>
