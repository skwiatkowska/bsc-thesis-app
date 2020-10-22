<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Author;
use App\Entities\Book;
use App\Entities\BookItem;
use App\Entities\Category;
use App\Entities\Publisher;
use App\Entities\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller {


    public function create() {
        $categories = Category::all();
        $authors = Author::all();
        $publishers = Publisher::all();

        if (count($categories) == 0) {
            return view('/admin/newBook', ['categories' => $categories, 'authors' => $authors, 'publishers' => $publishers])->withErrors("Brak kategorii w bazie danych. Dodaj najpierw kategorie, aby móc dodawać książki");
        }
        return view('/admin/newBook', ['categories' => $categories, 'authors' => $authors, 'publishers' => $publishers]);
    }


    public function store(Request $request) {
        //print_r($request->post());
        //Array ( [_token] => zU6M9DtxHgTwHBdyNEBzySqrJAecH8OdqBjHM4yp [title] => tytuł1 [authors] => Array ( [0] => guia ) [newAuthorName] => Array ( [0] => nowy1 [1] => ) [newAuthorLastName] => Array ( [0] => nowy2 [1] => ) [publisher] => aa [year] => 1998 [categories] => Array ( [0] => a [1] => b ) [numberOfItems] => 4 )
        $authors = $request->authors;
        $categories = $request->categories;
        $items = $request->numberOfItems;

        $categoriesToAssign = array();
        $authorsToAssign = array();
        $publisherToAssign = Publisher::find($request->publisher);
        $bookItemsToAssign = array();


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

        for ($i = 1; $i <= $items; $i++) {
            $item = array(
                'bookitem_id' => $i,
                'status' =>  BookItem::AVAILABLE,
                'is_blocked' => False
            );
            array_push($bookItemsToAssign, $item);
        }


        $book = Book::createWith(['title' => $request->title, 'isbn' => $request->isbn, 'publication_year' => $request->year], [
            'authors' => $authorsToAssign,
            'categories' => $categoriesToAssign,
            'publisher' => $publisherToAssign,
            'bookItems' => $bookItemsToAssign
        ]);

        return redirect('/pracownik/ksiazki/' . $book->id)->with(['success' => 'Dodano nową książkę: ' . $request->input('title')]);
    }


    public function index() {
        $categories = Category::all();
        $books = array();
        return view('/admin/catalog', ['categories' => $categories, 'books' => $books]);
    }


    public function fetchOneBook($id) {
        $book = Book::where('id', $id)->with('authors')->with('categories')->with('publisher')->with('bookItems.borrowings.user')->get()->first();
        return view('/admin/bookInfo', ['book' => $book]);
    }


    public function editBook($id) {
        $book = Book::where('id', $id)->with('authors')->with('categories')->with('publisher')->get()->first();
        $categories = Category::all();
        $authors = Author::all();
        $publishers = Publisher::all();
        return view('/admin/editBook', ['book' => $book, 'categories' => $categories, 'authors' => $authors, 'publishers' => $publishers]);
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
            $books = $category->books()->with('bookItems')->with('authors')->with('publisher')->get();
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
                return redirect('/pracownik/katalog')->withErrors("Nie znaleziono takiego autora: " . $phrase);
            }
            $authorIds = array();
            foreach ($authors as $author) {
                array_push($authorIds, $author->id);
            }

            $books = Author::find($authorIds[0])->books()->with('bookItems')->with('authors')->with('categories')->with('publisher')->get();

            foreach ($authorIds as $index => $authorId) {
                if ($index != 0) {
                    $subquery = Author::find($authorId)->books()->with('bookItems')->with('authors')->with('categories')->with('publisher')->get();
                    if ($subquery->count() > 0) {
                        $books = $books->merge($subquery);
                    }
                }
            }
            $searchInMode = "autor";
        } elseif ($searchIn == "publisher") {
            $publishers = Publisher::where('name', '=~', '.*' . $phrase . '.*')->get();
            if (!$publishers->count()) {
                return redirect('/pracownik/katalog')->withErrors("Nie znaleziono takiego wydawnictwa: " . $phrase);
            }
            $publisherIds = array();
            foreach ($publishers as $publisher) {
                array_push($publisherIds, $publisher->id);
            }

            $books = Publisher::find($publisherIds[0])->books()->with('bookItems')->with('authors')->with('categories')->with('publisher')->get();
            foreach ($publisherIds as $index => $publisherId) {
                if ($index != 0) {
                    $subquery = Publisher::find($publisherId)->books()->with('bookItems')->with('authors')->with('categories')->with('publisher')->get();
                    if ($subquery->count() > 0) {
                        $books = $books->merge($subquery);
                    }
                }
            }
            $searchInMode = "wydawnictwo";
        } elseif ($searchIn == "title") {
            $books = Book::where('title', '=~', '.*' . $phrase . '.*')->with('bookItems')->with('authors')->with('categories')->with('publisher')->get();
            $searchInMode = "tytuł";
        } elseif ($searchIn == "isbn") {
            $books = Book::where('isbn', $phrase)->with('bookItems')->with('authors')->with('categories')->with('publisher')->get();
            $searchInMode = "ISBN";
        }
        if (!$books->count()) {
            return redirect('/pracownik/katalog')->withErrors("Nie znaleziono książek spełniających podane kryterium wyszukiwania: " . $phrase . " (" . $searchInMode . ")");
        }
        return view('/admin/catalog', ['books' => $books, 'categories' => $categories, 'phrase' => $phrase]);
    }


    public function deleteBook(Request $request) {
        $book = Book::with('bookItems')->where('id', $request->id)->get()->first();
        if ($book->bookItems->count() > 0) {
            return back()->withErrors("Nie można usunąć książki z dostępnymi egzemplarzami");
        }
        $book->delete();     
        return redirect('/pracownik/katalog')->with("success", "Książka " . $book->title . " została usunięta na stałe");
    }



    // BOOK ITEMS FUNCTIONS
    public function fetchBookItem($id) {
        $item = BookItem::where('id', $id)->with('book')->with('borrowings.user')->get()->first();
        return view('/admin/bookItemInfo', ['item' => $item]);
    }


    public function blockUnlockBookItem(Request $request) {
        try {
            $item = BookItem::where('id', $request->id)->get()->first();
            $blocked = $item->is_blocked;
            $item->update(['is_blocked' => !$blocked]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Błąd podczas zmiany statusu egzemplarza']);
        }
        return response()->json(['success' => 'Status egzemplarza został zmieniony']);
    }

    public function storeBookItem(Request $request) {
        $book = Book::with('bookItems')->where('id', $request->bookId)->get()->first();
        foreach ($book->bookItems as $exisitingBookItem) {
            if ($exisitingBookItem->bookitem_id == $request->order) {
                return response()->json(['error' => 'Istnieje już egzemplarz o podanym numerze porządkowym: ' . $request->order]);
            }
        }

        BookItem::createWith([
            'bookitem_id' => $request->order,
            'status' =>  BookItem::AVAILABLE,
            'is_blocked' => False
        ], ['book' => $book]);
        return response()->json(['success' => 'Kolejny egzemplarz został pomyślnie dodany']);
    }

    public function deleteBookItem(Request $request) {
        $item = BookItem::with('book')->with('borrowings')->where('id', $request->id)->get()->first();
       if($item->borrowings->count()>0){
           foreach($item->borrowings as $b){
            $item->deleteRelatedBorrowing($b->id);

           }
       }

        $book = $item->book;
        $book->deleteRelatedBookItem($item->id);
        $item->delete();
        return response()->json(['success' => 'Egzemplarz został usunięty']);
    }


    
}
