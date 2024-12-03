<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!--=============== FAVICON ===============-->
        <link rel="shortcut icon" href="{{ asset('assets/img/MAYAH-STORE-LOGO.jpg') }}" type="image/x-icon">

        <!--=============== REMIXICONS ===============-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">

        <!--=============== BOXICONS ===============-->
        <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

        <!--=============== SWIPER CSS ===============-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

        <!--=============== CSS ===============-->
        <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">

        <!--=============== TOASTR ===============-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>

        <title>@yield('title', 'Mayah Store - Home')</title>
        @yield('styles')
    </head>

    <body>
    @yield('content')
      <!--=============== SWIPER JS ===============-->
      <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        
      <!--=============== MAIN JS ===============-->
      <script src="{{ asset('assets/js/main.js') }}"></script>

      <!--=============== TOASTER NOTIFICATION ===============-->
      <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    </body>
</html>
