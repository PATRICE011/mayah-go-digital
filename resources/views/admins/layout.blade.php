<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--=============== FAVICON ===============-->
    <link rel="shortcut icon" href="{{ asset('assets/img/MAYAH-STORE-LOGO.jpg') }}" type="image/x-icon">

    <!--=============== BOXICONS ===============-->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!--=============== REMIXICONS ===============-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">

    <!--=============== FONT AWESOME ===============-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!--=============== BOOTSTRAP CSS ===============-->
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/fonts/circular-std/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/fonts/fontawesome/css/fontawesome-all.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/vector-map/jqvmap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/jvectormap/jquery-jvectormap-2.0.2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/fonts/flag-icon-css/flag-icon.min.css') }}">


    <!--=============== TOASTR ===============-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">

    <!--=============== PAGE-SPECIFIC STYLES ===============-->
    @yield('styles')

    <title>@yield('title', 'Mayah Store Official - ADMIN')</title>
</head>


<body>

    <div class="dashboard-main-wrapper">
        @yield('content')
    </div>

    <!--=============== REQUIRED JS ===============-->
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Bootstrap Bundle -->
    <script src="{{ asset('assets/bootstrap/bootstrap/js/bootstrap.bundle.js') }}"></script>

    <!-- Include Chart.js -->
    <script src="{{ asset('assets/bootstrap/charts/charts-bundle/Chart.bundle.js') }}"></script>

    <!--=============== TOASTER NOTIFICATION ===============-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Include Custom admin.js -->
    <script src="{{ asset('assets/js/admin.js') }}"></script>
   
    
    <!-- Toastr Options -->
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
        };

        // Display success message if available
        @if(session('message'))
        toastr.success("{{ session('message') }}");
        @endif

        // Display error message if available
        @if(session('error'))
        toastr.error("{{ session('error') }}");
        @endif
    </script>
    <!--=============== PAGE-SPECIFIC SCRIPTS ===============-->
    @yield('scripts')
</body>

</html>