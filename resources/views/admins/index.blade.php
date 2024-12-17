@extends('admins.layout')
@section('content')

<h1>HELLO POOOO</h1>

<form action="{{route('admins.logout')}} " method="POST">
    @csrf
    <button type="submit" class="btn btn-danger">Logout</button>
</form>
@endsection