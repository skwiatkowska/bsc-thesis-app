<?php

namespace App\Http\Controllers\Admin;

use App\Models\Author;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthorController extends Controller {

    public function index() {
        $authors = Author::all();
        return view('/admin/authors', ['authors' => $authors]);
    }

    public function store(Request $request) {
        try {
            Author::create([
                'first_names' => $request->fname,
                'last_name' => $request->lname,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Autor ' . $request->fname . " " . $request->lname . ' już istnieje'], 409);
        }

        return response()->json(['success' => 'Autor ' . $request->fname . " " . $request->lname . ' dodany']);
    }

    public function update(Request $request, $id) {
        $author = Author::where('id', $id)->firstOrFail();
        if ($request->name == "fname" && $author->first_names != $request->value) {
            $author->first_names = $request->value;
        } else if ($request->name == "lname" && $author->last_name != $request->value) {
            $author->last_name = $request->value;
        }
        $author->save();
        return response()->json(['success' => 'Dane zostały zmienione']);
    }


    public function fetchAuthor($id) {
        $author = Author::where('id', $id)->with('books')->firstOrFail();
        return view('/admin/authorInfo', ['author' => $author]);
    }

    public function delete($id) {
        $author = Author::where('id', $id)->firstOrFail();
        $numberOfBooks = $author->books()->count();
        if ($numberOfBooks) {
            return back()->withErrors("Nie można usunąć autora z przypisanymi książkami");
        }
        $author->delete();
        return redirect('/pracownik/autorzy')->with(['success' => "Autor " . $author->last_name . " " . $author->first_names . " został usunięty"]);
    }
}
