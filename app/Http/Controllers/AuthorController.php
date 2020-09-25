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

    public function update(Request $request, $id) {
        $author = Author::where('id', $id)->get()->first();
        if($request->name == "fname" && $author->first_names != $request->value){
            $author->first_names = $request->value;
        }
        else if ($request->name == "lname" && $author->last_name != $request->value){
            $author->last_name = $request->value;
        }
        $author->save();
        return response()->json(['success' => 'Dane zostały zmienione']);
    }


    public function fetchAuthor($id){
        $author = Author::where('id', $id)->with('books')->get()->first();
        return view('/librarian/authorInfo', ['author' => $author]);

    }
}
