<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--=============== FAVICON ===============-->
    <link rel="shortcut icon" href="{{ asset('assets/img/MAYAH-STORE-LOGO.jpg') }}" type="image/x-icon">

    <!--=============== ICON LIBRARIES ===============-->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">

    <!--=============== BOOTSTRAP CSS (ONLY ONE VERSION) ===============-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!--=============== CUSTOM CSS ===============-->
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">

    <!-- ====== Toastr CSS ========-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Custom Styles -->
    @stack('styles')

    <title>@yield('title', 'Mayah Store Official - ADMIN')</title>
</head>

<body>
    <!-- Header and Dashboard -->
   
    @yield('content')
    
    <!--=============== SCRIPTS ===============-->

    <!-- Single jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap Bundle (JS + Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Custom Scripts -->
    <script src="{{ asset('assets/js/admin.js') }}"></script>

    <!-- Page-Specific Scripts -->
    @stack('scripts')
</body>

</html>
