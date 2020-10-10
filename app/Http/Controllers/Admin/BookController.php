<?php

namespace App\Http\Controllers\Admin;

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

        $book = Book::createWith(['title' => $request->title, 'isbn' => $request->isbn, 'publication_year' => $request->year, 'book_items_number' => $request->numberOfItems], [
            'authors' => $authorsToAssign,
            'categories' => $categoriesToAssign,
            'publisher' => $publisherToAssign
        ]);

        return redirect('/pracownik/ksiazki/' . $book->id)->with(['success' => 'Dodano nową książkę: ' . $request->input('title')]);
    }


    public function index() {
        $categories = Category::all();
        $books = array();
        return view('/librarian/catalog', ['categories' => $categories, 'books' => $books]);
    }


    public function fetchOneBook($id) {
        $book = Book::where('id', $id)->with('authors')->with('categories')->with('publisher')->get()->first();
        return view('/librarian/bookInfo', ['book' => $book]);
    }


    public function editBook($id) {
        $book = Book::where('id', $id)->with('authors')->with('categories')->with('publisher')->get()->first();
        $categories = Category::all();
        $authors = Author::all();
        $publishers = Publisher::all();
        return view('/librarian/editBook', ['book' => $book, 'categories' => $categories, 'authors' => $authors, 'publishers' => $publishers]);
    }


    public function update(Request $request, $id) {
        $book = Book::where('id', $id)->with('categories')->get()->first();

        if ($book->title != $request->title) {
            $book->title = $request->title;
        }
        if ($book->isbn != $request->isbn) {
            $book->isbn = $request->isbn;
        }
        if ($book->publication_year != $request->year) {
            $book->publication_year = $request->year;
        }
        if ($book->book_items_number != $request->numberOfItems) {
            $book->book_items_number = $request->numberOfItems;
        }

        if ($book->publisher->id != $request->publisher) {
            $book->deleteRelatedPublisher($book->publisher->id);
            $newPublisher = Publisher::where('id', $request->publisher)->get()->first();
            $newPublisher->books()->save($book);
        }

        //delete old categories
        foreach ($book->categories as $category) {
            $book->deleteRelatedCategory($category->id);
        }

        //attach new categories
        if (!empty($request->categories)) {
            foreach ($request->categories as $category) {
                $cat = Category::find($category);
                $book->categories()->save($cat);
            }
        }

        //delete old authors
        foreach ($book->authors as $author) {
            $book->deleteRelatedAuthor($author->id);
        }

        //attach new authors
        foreach ($request->authors as $author) {
            $a = Author::find($author);
            $book->authors()->save($a);
        }


        $book->save();
        return redirect("/pracownik/ksiazki/" . $book->id)->with(['success' => 'Dane książki zostały zaktualizowane']);
    }


    public function findBook(Request $request) {
        $categories = Category::all();
        $searchIn = $request->searchIn;
        $phrase = $request->phrase;
        $searchInMode = null;
        if ($searchIn == "category") {
            $phrase = $request->searchPhrase;
            $category = Category::where('id', $phrase)->get()->first();
            $books = $category->books()->with('authors')->with('publisher')->get();
            $phrase = $category->name;
            $searchInMode = "kategoria";
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

            if (!$authors->count()) {
                return redirect('/pracownik/katalog')->withErrors("Nie znaleziono takiego autora: ".$phrase);
            }
            $authorIds = array();
            foreach ($authors as $author) {
                array_push($authorIds, $author->id);
            }

            $books = Author::find($authorIds[0])->books()->with('authors')->with('categories')->with('publisher')->get();

            foreach ($authorIds as $index => $authorId) {
                if ($index != 0) {
                    $subquery = Author::find($authorId)->books()->with('authors')->with('categories')->with('publisher')->get();
                    if ($subquery->count() > 0) {
                        $books = $books->merge($subquery);
                    }
                }
            }
            $searchInMode = "autor";
        } elseif ($searchIn == "publisher") {
            $publishers = Publisher::where('name', '=~', '.*' . $phrase . '.*')->get();
            if (!$publishers->count()) {
                return redirect('/pracownik/katalog')->withErrors("Nie znaleziono takiego wydawnictwa: ".$phrase);
            }
            $publisherIds = array();
            foreach ($publishers as $publisher) {
                array_push($publisherIds, $publisher->id);
            }

            $books = Publisher::find($publisherIds[0])->books()->with('authors')->with('categories')->with('publisher')->get();
            foreach ($publisherIds as $index => $publisherId) {
                if ($index != 0) {
                    $subquery = Publisher::find($publisherId)->books()->with('authors')->with('categories')->with('publisher')->get();
                    if ($subquery->count() > 0) {
                        $books = $books->merge($subquery);
                    }
                }
            }
            $searchInMode = "wydawnictwo";
        } elseif ($searchIn == "title") {
            $books = Book::where('title', '=~', '.*' . $phrase . '.*')->with('authors')->with('categories')->with('publisher')->get();
            $searchInMode = "tytuł";
        } elseif ($searchIn == "isbn") {
            $books = Book::where('isbn', $phrase)->with('authors')->with('categories')->with('publisher')->get();
            $searchInMode = "ISBN";
        }
        if (!$books->count()) {
            return redirect('/pracownik/katalog')->withErrors("Nie znaleziono książek spełniających podane kryterium wyszukiwania: " . $phrase . " (" . $searchInMode . ")");
        }
        return view('/librarian/catalog', ['books' => $books, 'categories' => $categories, 'phrase' => $phrase]);
    }
}
