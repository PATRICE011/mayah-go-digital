<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Products</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 14px;
            text-align: left;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h2>Product List</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stocks</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->product_description }}</td>
                <td>{{ $product->category->category_name ?? 'N/A' }}</td>
                <td>₱{{ number_format($product->product_price, 2) }}</td>
                <td>{{ $product->product_stocks }}</td>
                <td>{{ $product->product_stocks > 0 ? 'Active' : 'Out of Stock' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        // Automatically trigger print
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
