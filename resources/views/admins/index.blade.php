<!-- @extends('admins.layout')
@include('admins.adminheader', ['activePage' => 'dashboard'])
@include('admins.dashboard')
@section('content')

<form action="{{route('admins.logout')}} " method="POST">
    @csrf
    <button type="submit" class="btn btn-danger">Logout</button>
</form>
@endsection -->