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

    public function store(Request $request) {
        try {
            Author::create([
                'first_names' => $request->fname,
                'last_name' => $request->lname,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Autor ' . $request->fname . " " .$request->lname . ' już istnieje'], 409);
        }

        return response()->json(['success' => 'Autor ' . $request->fname . " " .$request->lname . ' dodany']);
    }
}
