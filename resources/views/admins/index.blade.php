@extends('admins.layout')

@section('content')

    @include('admins.adminheader', ['activePage' => 'dashboard'])
    @include('admins.dashboard')
@endsection
