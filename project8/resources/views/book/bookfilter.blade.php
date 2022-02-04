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
                    <td>{{$book->bookAuthor->name}} {{$book->bookAuthor->surname}}</td>
                </tr>
                @endforeach
        </table>
    </div>
@endsection