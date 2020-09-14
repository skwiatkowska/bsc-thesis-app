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
        $book = Book::create([
            'title' => $request->input('title'),
        ]);

        $author = Author::create([
            'name' => $request->input('author'),
        ]);
        $book->authors()->save($author);

        return redirect('/pracownik')->with(['success' => 'Dodano nową książkę: '.$request->input('title')]);
    }

    public function index() {

            //$books = Book::all();

        //return view('/showBooks', ['books' => $books]);
        return view('/showBooks');
    }
}
