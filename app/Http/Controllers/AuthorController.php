<?php

namespace App\Http\Controllers;

use App\Entities\Author;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthorController extends Controller {



    public function index() {
        $authors = Author::all();
        return view('/librarian/authors', ['authors' => $authors]);
    }
}
