<?php

namespace App\Http\Controllers;

use App\Entities\Book;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller {


    public function create() {
        //$book = Book::create(['title' => 'aa']);

        return view('/librarian/newBook');
    }


    public function store(Request $request) {
        Book::create([
            'title' => $request->input('title'),
            'author' => $request->input('author'),
        ]);


        return redirect('/');
    }

    public function index() {

            //$books = Book::all();

        //return view('/showBooks', ['books' => $books]);
        return view('/showBooks');
    }
}
