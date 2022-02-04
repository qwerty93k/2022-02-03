@extends('layouts.app')

@section('content')
    <div class="containter">
        <table  class="table table-striped">
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Surname</th>
                <th>Username</th>
                <th>Description</th>
            </tr>
            @foreach ($authors as $author)
            <tr>
                <th>{{$author->id}}</th>
                <th>{{$author->name}}</th>
                <th>{{$author->surname}}</th>
                <th>{{$author->username}}</th>
                <th>{{$author->description}}</th>
            </tr>
            @endforeach
        </table>
    </div>
@endsection