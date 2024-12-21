@extends('admins.layout')
@section('content')
@include('admins.adminheader', ['activePage' => 'dashboard'])
<h1>STOWPEDD NI ANTHONYYYYYY</h1>

<form action="{{route('admins.logout')}} " method="POST">
    @csrf
    <button type="submit" class="btn btn-danger">Logout</button>
</form>
@endsection