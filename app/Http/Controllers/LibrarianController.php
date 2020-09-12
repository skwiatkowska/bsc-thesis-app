<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LibrarianController extends Controller
{
    public function index() {

        //$books = Book::all();

    //return view('/showBooks', ['books' => $books]);
    return view('/librarian/home');
}
}
