<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
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

        img {
            max-width: 50px;
            height: auto;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <h1>Categories Report</h1>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Category Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $index => $category)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <img src="{{ asset($category->category_image ? 'assets/img/' . $category->category_image : 'assets/img/default-placeholder.png') }}" alt="{{ $category->category_name }}">
                </td>
                <td>{{ $category->category_name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <button class="no-print" onclick="window.print()">Print Report</button>
</body>
</html>
