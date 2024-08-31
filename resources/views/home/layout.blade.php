<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--=============== FAVICON ===============-->
    <link rel="shortcut icon" href="{{ asset('assets/img/MAYAH-STORE-LOGO.jpg') }}" type="image/x-icon">

    <!--=============== REMIXICONS ===============-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">

    <!--=============== BOXICONS ===============-->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">


    <!--=============== CSS ===============-->
  
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">

      <!-- ====== toastr ========-->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
      <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <title>@yield('title', 'Mayah Store Official')</title>
    @yield('styles')
</head>
<body>
   
@yield('content')
    <!--=============== SCROLL REVEAL ANIMATION ===============-->
    <script src="{{ asset('assets/js/scrollreveal.min.js') }}"></script>
    
    <!--=============== MIXITUP FILTER ===============-->
    <script src="{{ asset('assets/js/mixitup.min.js') }}"></script>
    
    <!--=============== MAIN JS ===============-->
    <script src="{{ asset('assets/js/main.js') }}"></script>

     <!--===== toastr notif =====-->

    


   
</body>
</html>
