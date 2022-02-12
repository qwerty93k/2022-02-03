@extends('layouts.app')

@section('content')
<div class="container">
    <form method="GET" action="{{route('book.indexsortfilter')}}">
        @csrf
        <select name="sortCollumn">
            @foreach ($select_array as $key => $item)
            {{$key}}
            @if($item == $sortCollumn || ($key == 0 && empty($sortCollumn)) )
            <option value="{{$item}}" selected>{{$item}}</option>
            @else
            <option value="{{$item}}">{{$item}}</option>
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
        <select name="author_id">
            <option value="all">All</option>
            @foreach ($authors as $author)
                @if($author->id == $author_id)
                    <option value="{{$author->id}}" selected>{{$author->name}}</option>
                @else
                    <option value="{{$author->id}}">{{$author->name}}</option>
                @endif
            @endforeach
        </select>
        <select name="page_limit">
            @foreach ($paginationSettings as $setting)
                @if ($page_limit == $setting->value)
                <option value="{{$setting->value}}" selected>{{$setting->title}}</option>
                @else
                <option value="{{$setting->value}}">{{$setting->title}}</option>
                @endif
            @endforeach
        </select>
        <button type="submit">Sort</button>
    </form>
    <a href="{{route('book.indexsortfilter')}}" class="" type="submit">Clear Filter</a>

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
    @if ($page_limit != 1)
    {!!$books->appends(Request::except('page'))->render()!!}
    @endif
</div>
@endsection