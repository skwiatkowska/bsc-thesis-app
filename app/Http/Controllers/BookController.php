<?php

namespace App\Http\Controllers;

use App\Entities\Author;
use App\Entities\Book;
use App\Entities\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller {


    public function create() {
        $categories = Category::all();
        return view('/librarian/newBook', ['categories' => $categories]);
    }


    public function store(Request $request) {
        //print_r($request->post());
        //Array ( [_token] => zU6M9DtxHgTwHBdyNEBzySqrJAecH8OdqBjHM4yp [title] => tytuł1 [authors] => Array ( [0] => guia ) [newAuthorNames] => Array ( [0] => nowy1 [1] => ) [newAuthorLastName] => Array ( [0] => nowy2 [1] => ) [publisher] => aa [year] => 1998 [categories] => Array ( [0] => a [1] => b ) [numberOfItems] => 4 )
        $nAuthorNames = $request->newAuthorNames;
        $nAuthorLastName = $request->newAuthorLastName;

        // if (!empty($nAuthorNames) && !empty($nAuthorLastName)) {
        //     foreach ($nAuthorNames as $index => $names) {
        //         if (!empty($names)) {
        //             echo $index;
        //             $newAuthor = Author::create(['first_names' => $names, 'last_name' => $nAuthorLastName[$index]]);
        //         }
        //     }
        // }

        $book = Book::createWith(['title' => $request->title, 'publisher' => $request->publisher, 'publication_year' => $request->year], [
            'authors' => [
                [
                    'first_names' => 'fn',
                    'last_name'  => 'ln',
                ]
               
            ],
        
          
        ]);

        // $book = Book::create([
        //     'title' => $request->input('title'),
        // ]);

        // $author = Author::create([
        //     'name' => $request->input('author'),
        // ]);
        // $book->authors()->save($author);

        // return redirect('/pracownik')->with(['success' => 'Dodano nową książkę: '.$request->input('title')]);
    }

    public function index() {

        //$books = Book::all();

        //return view('/showBooks', ['books' => $books]);
        return view('/showBooks');
    }
}
