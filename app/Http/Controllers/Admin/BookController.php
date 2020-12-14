<?php

namespace App\Http\Controllers\Admin;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookItem;
use App\Models\Category;
use App\Models\Publisher;
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
        $authors = $request->authors;
        $categories = $request->categories;
        $items = $request->numberOfItems;

        $authorsToAssign = array();
        $bookItemsToAssign = array();
        $categoriesToAssign = array();
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

        for ($i = 1; $i <= $items; $i++) {
            $item = array(
                'book_item_id' => $i,
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


    public function fetchBook($id) {
        $book = Book::where('id', $id)->with('authors')->with('categories')->with('publisher')->with('bookItems.borrowings.user')->firstOrFail();
                return view('/admin/bookInfo', ['book' => $book]);
    }


    public function editBook($id) {
        $book = Book::where('id', $id)->with('authors')->with('categories')->with('publisher')->firstOrFail();
        $categories = Category::all();
        $authors = Author::all();
        $publishers = Publisher::all();
        return view('/admin/editBook', ['book' => $book, 'categories' => $categories, 'authors' => $authors, 'publishers' => $publishers]);
    }


    public function update(Request $request, $id) {
        $book = Book::where('id', $id)->with('categories')->firstOrFail();

        if ($book->title != $request->title) {
            $book->title = $request->title;
        }
        if ($book->isbn != $request->isbn) {
            $existingBook = Book::where('isbn', $request->isbn)->get();
            if ($existingBook->count() > 0) {
                return redirect('/pracownik/ksiazki/' . $book->id . '/edycja')->withErrors('Istnieje już książka o danym numerze ISBN');
            }
            $book->isbn = $request->isbn;
        }
        if ($book->publication_year != $request->year) {
            $book->publication_year = $request->year;
        }

        if ($book->publisher->id != $request->publisher) {
            $book->deleteRelatedPublisher($book->publisher->id);
            $newPublisher = Publisher::where('id', $request->publisher)->firstOrFail();
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
        
        if ($request->searchIn && ($request->phrase || $request->searchPhrase)) {
            $searchIn = $request->searchIn;
            $phrase = ucfirst($request->phrase);
            $searchInMode = null;
            if ($searchIn == "category") {
                $phrase = $request->searchPhrase;
                $category = Category::where('id', $phrase)->firstOrFail();
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
                    $books = collect();
                    return view('/admin/catalog', ['categories' => $categories, 'books' => $books])->withErrors("Nie znaleziono takiego autora: " . $phrase);
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
                    $books = collect();
                    return view('/admin/catalog', ['categories' => $categories, 'books' => $books])->withErrors("Nie znaleziono takiego wydawnictwa: " . $phrase);
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
                $books = Book::where('isbn', (int)$phrase)->with('bookItems')->with('authors')->with('categories')->with('publisher')->get();
                $searchInMode = "ISBN";
            }
            if (!$books->count()) {
                $books = collect();
                return view('/admin/catalog', ['categories' => $categories, 'books' => $books])->withErrors("Nie znaleziono książek spełniających podane kryterium wyszukiwania: " . $phrase . " (" . $searchInMode . ")");
            }
            return view('/admin/catalog', ['books' => $books, 'categories' => $categories, 'phrase' => $phrase]);
        } else {
            $books = Book::all();
            return view('/admin/catalog', ['categories' => $categories, 'books' => $books]);
        }
    }


    public function deleteBook(Request $request) {
        $book = Book::with('bookItems')->where('id', $request->id)->firstOrFail();
        if ($book->bookItems->count() > 0) {
            return redirect('/pracownik/ksiazki/' . $book->id)->withErrors("Nie można usunąć książki z dostępnymi egzemplarzami");
        }
        $book->delete();
        return redirect('/pracownik/katalog')->with("success", "Książka " . $book->title . " została usunięta na stałe");
    }



    // BOOK ITEMS FUNCTIONS
    public function fetchBookItem($id) {
        $item = BookItem::where('id', $id)->with('book')->with('borrowings.user')->firstOrFail();
        return view('/admin/bookItemInfo', ['item' => $item]);
    }


    public function blockUnlockBookItem(Request $request) {
        $item = BookItem::where('id', $request->id)->firstOrFail();
        $blocked = $item->is_blocked;
        if ($item->status != BookItem::AVAILABLE) {
            return response()->json(['error' => 'Egzemplarz nie jest dostępny. Status egzemplarza nie został zmieniony'], 403);
        }
        $item->update(['is_blocked' => !$blocked]);
        return response()->json(['success' => 'Status egzemplarza został zmieniony']);
    }



    public function storeBookItem(Request $request) {
        $book = Book::with('bookItems')->where('id', $request->bookId)->firstOrFail();
        foreach ($book->bookItems as $exisitingBookItem) {
            if ($exisitingBookItem->book_item_id == $request->order) {
                return response()->json(['error' => 'Istnieje już egzemplarz o podanym numerze porządkowym: ' . $request->order], 409);
            }
        }

        BookItem::createWith([
            'book_item_id' => $request->order,
            'status' =>  BookItem::AVAILABLE,
            'is_blocked' => False
        ], ['book' => $book]);
        return response()->json(['success' => 'Kolejny egzemplarz został pomyślnie dodany']);
    }

    public function deleteBookItem(Request $request) {
        $item = BookItem::with('book')->with('borrowings')->where('id', $request->id)->firstOrFail();
        if ($item->status == BookItem::AVAILABLE) {
            if (!empty($item->borrowings)) {
                foreach ($item->borrowings as $b) {
                    $item->deleteRelatedBorrowing($b->id);
                }
            }

            if (!empty($item->reservations)) {
                foreach ($item->reservations as $r) {
                    $item->deleteRelatedReservation($r->id);
                }
            }

            $book = $item->book;
            $book->deleteRelatedBookItem($item->id);
            $item->delete();
            return response()->json(['success' => 'Egzemplarz został usunięty']);
        } else {
            return response()->json(['error' => 'Nie można usunąć niedostępnego egzemplarza'], 403);
        }
    }
}
