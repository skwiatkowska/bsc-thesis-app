<?php

namespace App\Http\Controllers\Admin;

use App\Entities\BookItem;
use App\Entities\Borrowing;
use App\Entities\Reservation;
use App\Entities\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DateTime;

class ReservationController extends Controller {

    public function index() {
        $reservations = Reservation::with('user')->with('bookItem.book')->get();
        // dd($reservations);
        return view('/admin/reservations', ['reservations' => $reservations]);
    }


    public function borrowReservedBook(Request $request) {
        $user = User::where('id', $request->userId)->with('borrowings')->firstOrFail();
        $item = BookItem::with('book')->with('borrowings')->where('id', $request->bookItemId)->firstOrFail();
        if ($item->status == BookItem::BORROWED || $item->is_blocked) {
            return back()->withErrors("Ten egzemplarz jest już wypożyczony lub niedostępny");
        }

        $borrowing = new Borrowing(['borrow_date' => new DateTime(), 'due_date' => new DateTime("+1 month"), 'was_prolonged' => false]);
        $item->update(['status' => BookItem::BORROWED]);
        $user->borrowings($item)->save($borrowing);
        Reservation::where('id', $request->reservationId)->delete();
        return redirect('/pracownik/czytelnicy/' . $request->userId)->with(['success' => 'Książka ' . $item->book->title . ' została wypożyczona']);
    }
}
