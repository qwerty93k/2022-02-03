<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Models\Book;
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
        return view('author.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAuthorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //min kiek galime ivesti min simboliu
        //max kiek galime ivesti max simboliu
        //alpha tikrina ar ivestos tik raides
        //alpha_num tikra ar ivestos tik raides arba skaiciai
        //alpha_dash tikrina ar ivestos tik raides arba skaiciais, bet papildomai dar priima 2 simbolius: _ ir - 
        //numeric tikrina ar skaicius, integer(3.14, -5, 15, 0)
        //integer - tikrina ar sveikas skaicius (-, 0, +)

        //naturalus skaicius [1,+inf]
        //gt (greater than) gt:0
        //gte (greater than or equal) gte:0
        //lt(less than) lt:0
        //lte (less than or equal) lte:0
        //integer|>0

        //date - tikrina ar data
        //date_equals - tikrina ar data lygi
        //before - tikrina ar data yra ankstesne nei nurodyta
        //before_or_equal - tikrina ar data yra ankstesne arba lygi
        //after -tikrina ar data yra velesne
        //after_or_equal tikrina ar data yra velesne arba lygi

        // tikrinti ar ivestas lt telefono numeris?

        // +370 6 123 4567
        // +3706
        // string

        // 861324567 - tikrinti ar sveikas skaicius integer ir kiek skaitmenu yra skaiciuje (nevisai atitinka)


        // required
        //

        //regex - simboliu paieska pagal tam tikrus kriterijus/sablonus

        $request->validate([
            //kaireje puseje input laukelio vardas => desineje validacijos taisykle (kiekviena taisykle atskiriama | )
            "author_name" => "required|min:2|max:10",
            "author_surname" => "required|alpha",
            "author_username" => "required|alpha_num",
            "author_description" => "required|integer|gt:0",
            "number1" => "required",
            "number2" => "required|gt:number1", // lygina su number1
            "data1" => "required|date|date_equals:data2",
            "data2" => "required|date",
            //'phone' => "requred|regex:/(86|\+3706)\d{7}/'"
            'phone' => ["required", 'regex:/(86|\+3706)\d{7}/']
        ]);

        //if, jeigu sios taisykles patenkinamos, kodas vykdomas toliau
        //jeigu nors viena taisykle nera tenkinama, store funkcija nutraukiama ir grazina kintamaji errors

        $author = new Author;
        $author->name = $request->author_name;
        $author->surname = $request->author_surname;
        $author->username = $request->author_username;
        $author->description = $request->author_description;

        $author->save();

        //ar checkbox yra pazymetas, jeigu pazymetas pridedame ir autoriu ir knygas

        if ($request->author_newbooks) {

            //$request->book_title; masvyas ir jis yra tokio ilgio kiek turime input
            $books_count = count($request->book_title);

            for ($i = 0; $i < $books_count; $i++) {

                $book = new Book;
                $book->title = $request->book_title;
                $book->description = $request->book_description;
                $book->author_id = $author->id;
            }
        }

        return redirect()->route('author.index');
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
