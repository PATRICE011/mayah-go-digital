@extends('admins.layout')
@section('content')
@include('admins.adminheader', ['activePage' => 'dashboard'])
@include('admins.dashboard')
<form action="{{route('admins.logout')}} " method="POST">
    @csrf
    <button type="submit" class="btn btn-danger">Logout</button>
</form>
@endsection