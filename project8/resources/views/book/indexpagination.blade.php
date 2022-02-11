@extends('layouts.app')

@section('content')
    <div class="container">
        <form method="GET" action="{{route('book.indexpagination')}}">
            @csrf
            <select name="sortCollumn">
                @foreach ($select_array as $key => $item)  
                    @if($item == $sortCollumn || ($key == 0 && empty($sortCollumn)) )
                        <option value="{{$item}}" selected>{{$item}}</option>
                    @else
                        <option value="{{$item}}" >{{$item}}</option>
                    @endif
        
                @endforeach
            </select>
            <select name="sortOrder">
                @if($sortOrder == 'asc' || empty($sortOrder))
                    <option value="asc" selected>Ascending</option>
                    <option value="desc">Descending</option>
                @else
                    <option value="asc">Ascending</option>
                    <option value="desc" selected>Descending</option>
                @endif
            </select>
            <button type="submit">Sort</button>
        </form>
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
                    <td>{{$book->bookAuthor->name}} {{$book->bookAuthor->surname}}</td>
                </tr>
                @endforeach
        </table>
        {{--$books->links()--}}
        {!!$books->appends(Request::except('page'))->render()!!} {{-- prikabina visa requesta su issaugotais get--}}
    </div>
@endsection