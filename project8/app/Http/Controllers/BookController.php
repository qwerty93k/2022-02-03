<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\PaginationSetting;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $authors = Author::orderBy('name', 'asc')->get();
        $sortCollumn = $request->sortCollumn; //name
        $sortOrder = $request->sortOrder; //ASC

        $item_book = Book::all();
        $book_collumns = array_keys($item_book->first()->getAttributes());


        if (empty($sortCollumn) || empty($sortOrder)) {
            $books = Book::all();
        } else {

            if ($sortCollumn == "author_id") {
                $sortBool = true;
                if ($sortOrder == "asc") {
                    $sortBool = false;
                }

                $books = Book::get()->sortBy(function ($query) {
                    return $query->bookAuthor->name;
                }, SORT_REGULAR, $sortBool)->all();
            } else {
                $books = Book::orderBy($sortCollumn, $sortOrder)->get();
            }

            //$books = Book::orderby($sortCollumn, $sortOrder)->get();

            // rikiuosime pagal paskutini stulpeli, autoriaus varda
            // true/false

        }

        $select_array = $book_collumns;
        //$select_array = ['author'];

        return view('book.index', ['books' => $books, 'authors' => $authors, 'sortCollumn' => $sortCollumn, 'sortOrder' => $sortOrder, 'select_array' => $select_array,]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $authors = Author::all();
        return view('book.create', ['authors' => $authors]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBookRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $book = new Book;

        $book->title = $request->book_title;
        $book->description = $request->book_description;
        //jeigu checkbox pazymi kuria nauja autoriu

        if ($request->book_newauthor) {

            $author = new Author;

            $author->name = $request->author_name;
            $author->surname = $request->author_surname;
            $author->username = $request->author_username;
            $author->description = $request->author_description;
            $author->save();

            $book->author_id = $author->id;
        } else {
            $book->author_id = $request->book_authorid;
        }

        $book->save();

        return redirect()->route('book.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBookRequest  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        //
    }

    public function bookFilter(Request $request)
    {
        // 1. filtras turi viena inpute kuriame yra select
        // 2. tame selecte yra atvaizduojami visi autoriai
        // 3. pasirinktas autorius yra perduodamas per forma i bookfilter funkcija
        // 4. ir pagal autoriaus kintamaji mes vykdome filtravima

        //$books = Book::all();

        $author_id = $request->author_id;
        $books = Book::where('author_id', '=', $author_id)->get();
        return view('book.bookfilter', ['books' => $books]);
    }

    public function indexPagination(Request $request)
    {

        $sortCollumn = $request->sortCollumn; //name
        $sortOrder = $request->sortOrder; //ASC

        //rikiavimas

        $item_book = Book::all();
        $book_collumns = array_keys($item_book->first()->getAttributes());


        if (empty($sortCollumn) || empty($sortOrder)) {
            $books = Book::paginate(15);
        } else {
            $books = Book::orderBy($sortCollumn, $sortOrder)->paginate(15);
        }

        $select_array = $book_collumns;

        //paprastasis puslapiavimas
        //simplePaginate

        //pilnasis puslapiavimas
        //paginate

        //isrikiuoti elementus pagal id mazejimo tvarka

        //$books = Book::all()->sortBy('id', SORT_REGULAR, true);

        //$books = Book::orderBy('id', 'DESC')->paginate(15);

        //$books = Book::paginate(15);

        return view('book.indexpagination', ['books' => $books, 'sortCollumn' => $sortCollumn, 'sortOrder' => $sortOrder, 'select_array' => $select_array]);
    }

    private function isPaginateFilter($page_limit, $author_id, $sortCollumn, $sortOrder)
    {
        if ($page_limit == 1) {
            return $books = Book::where('author_id', '=', $author_id)->orderBy($sortCollumn, $sortOrder)->get();
        }
        return $books = Book::where('author_id', '=', $author_id)->orderBy($sortCollumn, $sortOrder)->paginate($page_limit);
    }

    private function isPaginateSort($page_limit, $sortCollumn, $sortOrder)
    {
        if ($page_limit == 1) {
            //nera puslpaiavimo
            if ($sortCollumn == 'author_id') {
                return $books = Book::select('books.*')->join('authors', 'books.author_id', '=', 'authors.id')->orderBy('authors.name', $sortOrder)->get();
            }
            return $books = Book::orderBy($sortCollumn, $sortOrder)->get();
        } else {
            //ir kai yra puslapiavimas
            if ($sortCollumn == 'author_id') {
                return $books = Book::select('books.*')->join('authors', 'books.author_id', '=', 'authors.id')->orderBy('authors.name', $sortOrder)->paginate($page_limit);
            }

            return $books = Book::orderBy($sortCollumn, $sortOrder)->paginate($page_limit);
        }
        return 0;
    }

    public function indexsortfilter(Request $request)
    {
        $sortCollumn = $request->sortCollumn;
        $sortOrder = $request->sortOrder;
        $author_id = $request->author_id; // select = 'all'


        $paginationSettings = PaginationSetting::where('visible', '=', 1)->get();

        $sortBool = true;

        if ($sortOrder == "asc") {
            $sortBool = false;
        }

        $page_limit = $request->page_limit;

        //Rikiavimo stulpelius
        $tem_book = Book::all();
        $book_collumns = array_keys($tem_book->first()->getAttributes());
        $select_array =  $book_collumns;

        if (empty($sortCollumn) || empty($sortOrder) || empty($author_id)) {
            $books = Book::paginate($page_limit);
        } else {
            if ($author_id == 'all') {
                $books = $this->isPaginateSort($page_limit, $sortCollumn, $sortOrder);
            } else {
                $books = $this->isPaginateFilter($page_limit, $author_id, $sortCollumn, $sortOrder);
            }
        }
        $authors = Author::all();

        return view('book.indexsortfilter', [
            'authors' => $authors,
            'sortCollumn' => $sortCollumn,
            'sortOrder' => $sortOrder,
            'select_array' => $select_array,
            'books' => $books,
            'author_id' => $author_id,
            'paginationSettings' => $paginationSettings,
            'page_limit' => $page_limit
        ]);
    }
    public function indexsortable(Request $request)
    {
        $author_id = $request->author_id;
        $page_limit = $request->page_limit;

        $sort  = $request->sort;
        $direction  = $request->direction;

        //atfiltruoti duomenis kur author_id = 2
        $authors = Author::all();
        $paginationSettings = PaginationSetting::where('visible', '=', 1)->get();

        if (empty($author_id) || $author_id == 'all') {
            if ($page_limit == 1) {
                //rodomi visi irasai
                $books = Book::sortable()->get();
            } else {
                $books = Book::sortable()->paginate($page_limit);
            }
        } else {
            if ($page_limit == 1) {
                //rodomi visi irasai
                $books = Book::where('author_id', '=', $author_id)->sortable()->get();
            } else {
                $books = Book::where('author_id', '=', $author_id)->sortable()->paginate($page_limit);
            }
        }

        //paginate - yra biblioteka integruota laravely
        //rikiavimo bibliotekos laravel neturi, reik pasidaryt/parsisiust?
        return view('book.indexsortable', [
            'books' => $books,
            'authors' => $authors,
            'paginationSettings' => $paginationSettings,
            'authors' => $authors,
            'author_id' => $author_id,
            'page_limit' => $page_limit,
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }

    public function indexadvancedsort()
    {
        $books = Book::select('*')->join('authors', 'books.author_id', '=', 'authors.id')->orderBy('authors.name', 'ASC')->paginate(5); //prijungti autoriu lenteles autoriaus id stulpeli, ge tsuformuoja kaip kolekcija/masyva

        return view('book.indexadvancedsort', ['books' => $books]);
    }
}
