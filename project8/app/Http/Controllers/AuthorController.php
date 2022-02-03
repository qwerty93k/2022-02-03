<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 1. rikiavimas - duomenu kiekis nesikeicia, keicias tvarka

        // 2. filtravimas - duomenu kiekis keiciasi, pagal priskirta atributa

        //$authors = Author::all(); // musu duomenys paimti is db, masyvas

        //$authors = Author::sort(); //sioje vietoje sort ta pati daro kas all, rikiuoja pagal id didejimo tvarka, reikia nurodyti parametra koki nors.
        //sort naudojam kai gaunami sumaisyta/neisrikiuota masyva is db, tuomet sort viska surikiuoja
        //sortBy() - galim pasirinkti pagal kuri stulpeli galime rikiuoti, galime keisti eiliskuma.
        //DESC mazeja, ASC dideja

        // pakeiciam rikiavima pagal id tik mazejancia tvarka

        //true -
        //false - ???

        $authors = Author::all()->sortBy('id', SORT_REGULAR, true);

        //$authors - kolekcija
        //kolekcija - tam tikras isplestas masyvas kuris savyje turi duomenu apdorojimo funkcijas
        //kolekcija visada rikiuojama pagal id stulpeli didejimo tvarka (default)

        return view('author.index', ['authors' => $authors]);
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
