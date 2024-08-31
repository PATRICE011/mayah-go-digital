
@extends('home.layout')
@section('title')
@section('content')
      <!--==================== HEADER ====================-->
      @include('users.header')

      <!--==================== CART ====================-->
      @include('home.cartinside')
      <!--==================== SEARCH ====================-->
      @include('home.search')
      <!--==================== LOGIN/REGISTER ====================-->
      @include('home.users')
      
      <!--==================== MAIN ====================-->
      @include('home.main')

      <!--==================== FOOTER ====================-->
      @include('home.footer')

      @if (Session::has('message'))
      <script>
            toastr.options = {
                  "closeButton": true,
                  "progressBar": true,
                  "positionClass": "toast-top-right",
                  "timeOut": "5000",
            };

            toastr.success("{{ Session::get('message') }}");
      </script>
    @endif
@endsection
    
      
    