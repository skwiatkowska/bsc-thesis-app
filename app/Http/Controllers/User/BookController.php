<?php

namespace App\Http\Controllers\User;

use App\Entities\Author;
use App\Entities\Book;
use App\Entities\Category;
use App\Entities\Publisher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller {

    public function index() {
        $categories = Category::all();
        $books = array();
        return view('/catalog', ['categories' => $categories, 'books' => $books]);
    }


    public function fetchOneBook($id) {
        $book = Book::where('id', $id)->with('authors')->with('categories')->with('publisher')->get()->first();
        return view('/bookInfo', ['book' => $book]);
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
                return redirect('/katalog')->withErrors("Nie znaleziono takiego autora: ".$phrase);
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
                return redirect('/katalog')->withErrors("Nie znaleziono takiego wydawnictwa: ".$phrase);
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
            return redirect('/katalog')->withErrors("Nie znaleziono książek spełniających podane kryterium wyszukiwania: " . $phrase . " (" . $searchInMode . ")");
        }
        return view('/catalog', ['books' => $books, 'categories' => $categories, 'phrase' => $phrase]);
    }
}
