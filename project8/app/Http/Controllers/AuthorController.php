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

        // filtravimo pavyzdys - paieska, paieskos puslapis nukreipia i kita puslapi su rezultatais


        $sortCollumn = $request->sortCollumn; //name
        $sortOrder = $request->sortOrder; //ASC

        if (empty($sortCollumn) || empty($sortOrder)) {
            $authors = Author::all();
        } else {
            $authors = Author::orderby($sortCollumn, $sortOrder)->get();
        }

        $select_array = array_keys($authors->first()->getAttributes());
        //$authors - kolekcija, sudetingesnis masytas
        // first() - grazina viena irasa is kolekcijos
        // kolekcijos objektas - informacija apie objekta yra kur kas giliau, su foreach'u nepasieksi
        // reikia info apie objekta, raktu ir informacijos, is to ir atsiranda funkcija "getAttributes()"
        // ji gauna apie kolekcija info kaip apie paprasta masyva
        //array_keys galima pasiimti is DB stulpeliu pavadinimus

        //alt variantas $select_array = DB::getSchemaBuilder()->getColumnListing('authors');
        //kreipiasi tiesiai i DB, paima struktura, ir kaip masyva grazina DB stulpeliu pavadinimus,
        // bet geriau controlery nenaudoti sio, geriau imesti i modeli.
        // sitai funkcijai reik isimesti "use \Illuminate\Support\Facades\DB;" biblioteka

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

    public function search(Request $request)
    {   //$authors = Author::all();

        //atrinkti autoriu/ius kurio/iu id = 34

        $search_key = $request->search_key; //$request->search_key, ateis is imput lauko

        // AND ir OR
        // description panasus yra i paieskos zodi arba(OR) vardas panasus i paieskos zodi (pagal du stulpelius vykdoma paieska)
        // description panasus yra i paieskos zodi arba(OR) vardas panasus i paieskos zodi OR pavarde panasu i paieskos zodi (pagal 3 stulpelius vykdoma paieska)

        // AND abu stulpeliai turi atitikti paieskos zodi

        $authors = Author::where('description', 'LIKE', '%' . $search_key . '%')
            ->orWhere('name', 'LIKE', '%' . $search_key . '%')
            ->orWhere('surname', 'LIKE', '%' . $search_key . '%')
            ->orWhere('username', 'LIKE', '%' . $search_key . '%')
            ->orWhere('description', 'LIKE', '%' . $search_key . '%')
            ->get();

        //where()
        // 1 parametras - stulpelio pavadinimas
        // 2 - default yra reiskme, nebent nurodome treciaji (jeigu 3 nurodytas, 2 parametras yra operacijos veiksmas)
        // 3 - reiksme
        // where('name', '=', 'jane'); Operacijos veiksmai gali buti >,<,<=,=>,=, LIKE
        // %% iesko teksto kuris turi specifini simboli, kaip siuo atveju 3

        //where() griezta salyga
        //imu autorius kur yra kazkokia salyga (id, 34)(stulpelis, reiksme)


        return view('author.search', ['authors' => $authors]);
    }
}
