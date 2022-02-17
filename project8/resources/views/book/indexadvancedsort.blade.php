@extends('layouts.app')

@section('content')
    <div class="container">
        <table class="table table-striped">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Author ID</th>
            </tr>

                @foreach ($books as $book)
                <tr>
                    <td>{{$book->id}}</td>
                    <td>{{$book->title}}</td>
                    <td>{{$book->description}}</td>
                    {{--autoriaus varda ir pavarde reik atvaizduoti, reik modeli sukurti rysio--}}
                    <td>{{$book->name}} {{$book->surname}}</td>
                </tr>
                @endforeach
        </table>
    </div>
@endsection