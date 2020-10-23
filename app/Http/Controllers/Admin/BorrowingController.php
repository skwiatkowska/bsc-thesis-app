<?php

namespace App\Http\Controllers\Admin;

use App\Entities\BookItem;
use App\Entities\Borrowing;
use App\Entities\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DateTime;

class BorrowingController extends Controller {

    public function index() {
        $borrowings = Borrowing::with('user')->with('bookItem.book')->get();
        // dd($borrowings);
        return view('/admin/borrowings', ['borrowings' => $borrowings]);
    }

    public function borrowBookItemAddUser($id) {
        $item = BookItem::with('book')->where('id', $id)->get()->first();
        $book = $item->book::with('authors')->get()->first();
        return view('/admin/addUserToBorrowing', ['item' => $item, 'book' => $book, 'users' => '']);
    }


    public function borrowBookItemFindUser(Request $request, $id) {
        $searchIn = $request->searchIn;
        $phrase = $request->phrase;
        if ($searchIn == "pesel") {
            $users = User::where('pesel', $phrase)->get();
        } elseif ($searchIn == "lname") {
            $users = User::where('last_name', '=~', '.*' . $phrase . '.*')->get();
            if (!$users->count()) {
                return back()->withErrors("Nie znaleziono takiego Czytelnika: " . $phrase);
            }
        }
        $item = BookItem::with('book')->where('id', $id)->get()->first();
        $book = $item->book::with('authors')->get()->first();
        return view('/admin/addUserToBorrowing', ['item' => $item, 'users' => $users, 'book' => $book, 'phrase' => $phrase]);
    }

    public function borrowBook(Request $request) {
        $user = User::where('id', $request->userId)->with('borrowings')->get()->first();
        $item = BookItem::with('book')->with('borrowings')->where('id', $request->bookItemId)->get()->first();
        if ($item->status != BookItem::AVAILABLE || $item->is_blocked) {
            return back()->withErrors("Ten egzemplarz jest już wypożyczony lub niedostępny");
        }

        $borrowing = new Borrowing(['borrow_date' => new DateTime(), 'due_date' => new DateTime("+1 month"), 'was_prolonged' => false]);
        $item->update(['status' => BookItem::BORROWED]);
        $user->borrowings($item)->save($borrowing);
        return redirect('/pracownik/czytelnicy/' . $request->userId)->with(['success' => 'Książka ' . $item->book->title . ' została wypożyczona']);
    }

    public function prolongBookItem(Request $request) {
        $item = BookItem::with('book')->with('borrowings')->where('id', $request->id)->get()->first();
        foreach ($item->borrowings as $borrowing) {
            if (!isset($borrowing->actual_return_date) && !$borrowing->was_prolonged) {
                $due_date = new DateTime($borrowing->due_date);
                $new_due_date = $due_date->modify('+1 month');
                $borrowing->update(['due_date' => $new_due_date, 'was_prolonged' => true]);
            }
        }
        return response()->json(['success' => 'Czas na oddanie książki został przedłużony o 1 miesiąc']);
    }

    public function returnBookItem(Request $request) {
        $item = BookItem::with('borrowings')->where('id', $request->id)->get()->first();
        foreach ($item->borrowings as $borrowing) {
            if (!isset($borrowing->actual_return_date)) {
                $borrowing->update(['actual_return_date' => new DateTime()]);
                $item->update(['status' => BookItem::AVAILABLE]);
                if ($borrowing->due_date < new DateTime()) {
                    $now = new DateTime();
                    $interval = $now->diff(new DateTime($borrowing->due_date));
                    $fee = $interval->d * 0.5;
                    $borrowing->overdue_fee = $fee;
                }
            }
        }
        // return response()->json(['success' => 'Egzemplarz został zwrócony']);
        return back()->with('success', 'Egzemplarz został zwrócony');
    }
}
