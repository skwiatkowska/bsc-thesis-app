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

        return view('/librarian/newBook', ['categories' => $categories, 'authors' => $authors, 'publishers' => $publishers]);
    }


    public function store(Request $request) {
        //print_r($request->post());
        //Array ( [_token] => zU6M9DtxHgTwHBdyNEBzySqrJAecH8OdqBjHM4yp [title] => tytuł1 [authors] => Array ( [0] => guia ) [newAuthorName] => Array ( [0] => nowy1 [1] => ) [newAuthorLastName] => Array ( [0] => nowy2 [1] => ) [publisher] => aa [year] => 1998 [categories] => Array ( [0] => a [1] => b ) [numberOfItems] => 4 )
        $nAuthorName = $request->newAuthorName;
        $nAuthorLastName = $request->newAuthorLastName;
        $existingAuthors = $request->authors;
        $categories = $request->categories;

        $categoriesToAssign = array();
        $authorsToAssign = array();
        $publisherToAssign = array();


        //retrieve categories for db
        if (!empty($categories)) {
            foreach ($categories as $category) {
                $cat = Category::find($category);
                array_push($categoriesToAssign, $cat);
            }
        }

        //retrieve existing authors from db
        if (!empty($existingAuthors)) {
            foreach ($existingAuthors as $existAuthor) {
                $author = Author::find($existAuthor);
                array_push($authorsToAssign, $author);
            }
        }

        //new authors
        if (!empty($nAuthorName) && !empty($nAuthorLastName)) {
            foreach ($nAuthorName as $index => $name) {
                if (!empty($name)) {
                    $newAuthor = array(
                        "first_names" => $name,
                        "last_name" =>  $nAuthorLastName[$index]
                    );
                    array_push($authorsToAssign, $newAuthor);
                }
            }
        }

        if (!empty($request->newPublisher)) {
            $publisherToAssign = array(
                "name" => $request->newPublisher
            );
        } else {
            $publisherToAssign = Publisher::find($request->publisher);
        }

        $book = Book::createWith(['title' => $request->title, 'publisher' => $request->publisher, 'publication_year' => $request->year], [
            'authors' => $authorsToAssign,
            'categories' => $categoriesToAssign,
            'publisher' => $publisherToAssign
        ]);

        return redirect('/pracownik')->with(['success' => 'Dodano nową książkę: ' . $request->input('title')]);
    }

    public function index() {
        $categories = Category::all();

        //$books = Book::all();

        //return view('/showBooks', ['books' => $books]);
        return view('/librarian/catalog', ['categories' => $categories]);
    }
}
