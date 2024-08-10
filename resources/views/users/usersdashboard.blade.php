
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
@endsection
    
      
    