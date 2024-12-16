<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--=============== FAVICON ===============-->
    <link rel="shortcut icon" href="{{ asset('assets/img/MAYAH-STORE-LOGO.jpg') }}" type="image/x-icon">

    <!--=============== BOXICONS ===============-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!--=============== REMIXICONS ===============-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">

    <!--=============== BOOTSTRAP CSS ===============-->
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/fonts/circular-std/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/fonts/fontawesome/css/fontawesome-all.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/vector-map/jqvmap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/jvectormap/jquery-jvectormap-2.0.2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/fonts/flag-icon-css/flag-icon.min.css') }}">

    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">

     <!-- ====== toastr ========-->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <title>@yield('title', 'Mayah Store Official - ADMIN')</title>
    @yield('styles')
</head>

<body>
    @yield('content')

    <script src="{{ asset('assets/bootstrap/bootstrap/js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/slimscroll/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/charts/charts-bundle/Chart.bundle.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/charts/charts-bundle/chartjs.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/jvectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/charts/sparkline/jquery.sparkline.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/charts/sparkline/spark-js.js') }}"></script>
    <script src="{{ asset('assets/js/admin.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
</body>
</html>
