<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;

use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) // paimsim duomenis is GET
    {
        // 1. rikiavimas - duomenu kiekis nesikeicia, keicias tvarka

        // 2. filtravimas - duomenu kiekis keiciasi, pagal priskirta atributa

        //$authors = Author::all(); // musu duomenys paimti is db, masyvas

        //$authors = Author::sort(); //sioje vietoje sort ta pati daro kas all, rikiuoja pagal id didejimo tvarka, reikia nurodyti parametra koki nors.

        //$authors - kolekcija
        //kolekcija - tam tikras isplestas masyvas kuris savyje turi duomenu apdorojimo funkcijas
        //kolekcija visada rikiuojama pagal id stulpeli didejimo tvarka (default)



        //sort naudojam kai gaunami sumaisyta/neisrikiuota masyva is db, tuomet sort viska surikiuoja
        //sortBy() - galim pasirinkti pagal kuri stulpeli galime rikiuoti, galime keisti eiliskuma.
        //DESC mazeja, ASC dideja
        //orderBy() 

        // pakeiciam rikiavima pagal id tik mazejancia tvarka

        //true - DESC, pagal skaiciu, raide
        //false - ASC

        // $authors = Author::all()->sortBy('name', SORT_REGULAR, true);
        // iki 100 objektu tinka, jeigu dideli kiekiai geriau naudot orderby, veikia greiciau


        $sortCollumn = $request->sortCollumn; //name
        $sortOrder = $request->sortOrder; //ASC

        if (empty($sortCollumn) || empty($sortOrder)) {
            $authors = Author::all();
        } else {
            $authors = Author::orderby($sortCollumn, $sortOrder)->get();
        }

        $select_array = ['id', 'name', 'surname', 'username', 'description'];
        // 0(raktas) - id(reiksme)
        // 1(raktas) - name(reiksme)

        //$select_array = [];
        //foreach ($autorius as $autoriaus_parametras) {
        //    $select_array[] = $autoriaus_parametras;
        //}


        //$authors = Author::orderBy($sortCollumn, $sortOrder)->get(); //negalime nurodyti algoritmo pagal kuri rikiuojama

        //1000 autoriu
        //kreipiasi i DB pasiima viska, sudeda i kolekcija ir rikiuoja pacia kolekcija
        //uz si veiksma atsakingas serveris, jame isrikuoja ir grazina i ekrana


        // 1. index.blade.php reikia formos
        // 2. GET metodas geriau, nes jokiu slaptu duomenu // parametrai perduodami per get
        // galima pasirinkti rikiavimo stulpeli ir tvarka.
        // 3. mygtukas SORT
        // 4. action // index.blade.php/route("author.index")/'' 

        return view('author.index', ['authors' => $authors, 'sortCollumn' => $sortCollumn, 'sortOrder' => $sortOrder, 'select_array' => $select_array,]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAuthorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAuthorRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function edit(Author $author)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAuthorRequest  $request
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAuthorRequest $request, Author $author)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy(Author $author)
    {
        //
    }
}
