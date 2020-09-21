<?php

namespace App\Http\Controllers;

use App\Entities\Author;
use App\Entities\Book;
use App\Entities\Category;
use App\Entities\Publisher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller {


    public function create() {
        $categories = Category::all();
        $authors = Author::all();
        $publishers = Publisher::all();

        if (count($categories) == 0) {
            return view('/librarian/newBook', ['categories' => $categories, 'authors' => $authors, 'publishers' => $publishers])->withErrors("Brak kategorii w bazie danych. Dodaj najpierw kategorie, aby móc dodawać książki");
        }
        return view('/librarian/newBook', ['categories' => $categories, 'authors' => $authors, 'publishers' => $publishers]);
    }


    public function store(Request $request) {
        //print_r($request->post());
        //Array ( [_token] => zU6M9DtxHgTwHBdyNEBzySqrJAecH8OdqBjHM4yp [title] => tytuł1 [authors] => Array ( [0] => guia ) [newAuthorName] => Array ( [0] => nowy1 [1] => ) [newAuthorLastName] => Array ( [0] => nowy2 [1] => ) [publisher] => aa [year] => 1998 [categories] => Array ( [0] => a [1] => b ) [numberOfItems] => 4 )
        $authors = $request->authors;
        $categories = $request->categories;

        $categoriesToAssign = array();
        $authorsToAssign = array();
        $publisherToAssign = Publisher::find($request->publisher);

        $isbn = $request->isbn;
        if (Book::where('isbn', $isbn)->count() > 0) {
            return back()->withErrors("W bazie istnieje już książka o podanym numerze ISBN");
        }

        //retrieve categories for db
        if (!empty($categories)) {
            foreach ($categories as $category) {
                $cat = Category::find($category);
                array_push($categoriesToAssign, $cat);
            }
        }

        //retrieve authors from db
        if (!empty($authors)) {
            foreach ($authors as $author) {
                $a = Author::find($author);
                array_push($authorsToAssign, $a);
            }
        }




        $book = Book::createWith(['title' => $request->title, 'isbn' => $request->isbn, 'publisher' => $request->publisher, 'publication_year' => $request->year], [
            'authors' => $authorsToAssign,
            'categories' => $categoriesToAssign,
            'publisher' => $publisherToAssign
        ]);

        return redirect('/pracownik')->with(['success' => 'Dodano nową książkę: ' . $request->input('title')]);
    }

    public function index() {
        $categories = Category::all();

        $books = Book::all();

        //return view('/showBooks', ['books' => $books]);
        return view('/librarian/catalog', ['categories' => $categories, 'books' => '']);
    }


    public function findBook(Request $request) {
        $categories = Category::all();

        //dd($request->post());
        $searchIn = $request->searchIn;
        $phrase = $request->phrase;
        if ($searchIn == "category") {
            $phrase = $request->searchPhrase;
            $id = $phrase;

            $books = Category::find($id)->books()->with('authors')->with('publisher')->get();
        } elseif ($searchIn == "author") {
            $words = explode(" ", $phrase);
            if (count($words) > 1) {
                $authors = Author::where('last_name', '=~', '.*' . $words[0] . '.*')->get();

                foreach ($words as $index => $word) {
                    if ($index != 0) {
                        $subauthor = Author::where('last_name', '=~', '.*' . $word . '.*')->get();
                        $authors = $authors->merge($subauthor);
                    }
                }
            } else {
                $authors = Author::where('last_name', '=~', '.*' . $words[0] . '.*')->get();
            }
            //dd($authors);
            $authorIds = array();
            foreach ($authors as $author) {
                array_push($authorIds, $author->id);
            }

            //dd($authorIds);
            $books = Author::find($authorIds[0])->books()->with('authors')->with('categories')->with('publisher')->get();

            foreach ($authorIds as $index => $authorId) {
                if ($index != 0) {
                    $subbooks = Author::find($authorId)->books()->with('authors')->with('categories')->with('publisher')->get();
                    if ($subbooks->count() > 0) {
                        $books = $books->merge($subbooks);
                    }
                }
            }
            dd($books);
        } elseif ($searchIn == "publisher") {
            $publisher = Publisher::where('name', '=~', '.*' . $phrase . '.*')->get()->first();
            $books = Publisher::find($publisher->id)->books()->with('authors')->with('categories')->with('publisher')->get();
        }

        return view('/librarian/catalog', ['books' => $books, 'categories' => $categories]);
    }
}
