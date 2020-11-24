<?php

namespace App\Http\Controllers\Admin;

use App\Models\BookItem;
use App\Models\Borrowing;
use App\Models\Reservation;
use App\Models\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DateTime;

class ReservationController extends Controller {

    public function index() {
        $now = new \DateTime();
        $expired = Reservation::with('bookItem')->where('due_date', '<', $now)->get();
        foreach ($expired as $exp) {
            $item = $exp->bookItem;
            $item->update(['status' => BookItem::AVAILABLE]);
            $exp->delete();
        }
        $reservations = Reservation::with('user')->with('bookItem.book')->get();
        return view('/admin/reservations', ['reservations' => $reservations]);
    }


    public function borrowReservedBook(Request $request) {
        $user = User::where('id', $request->userId)->with('borrowings')->firstOrFail();
        $item = BookItem::with('book')->with('borrowings')->where('id', $request->bookItemId)->firstOrFail();
        $reservation = Reservation::where('id', $request->reservationId)->get()->first();
        if ($item->status == BookItem::BORROWED || $item->is_blocked) {
            return back()->withErrors("Ten egzemplarz jest już wypożyczony lub niedostępny");
        }
        if ($reservation->user->id == $user->id) {
            $borrowing = new Borrowing(['borrow_date' => new DateTime(), 'due_date' => new DateTime("+1 month"), 'was_prolonged' => false]);
            $item->update(['status' => BookItem::BORROWED]);
            $user->borrowings($item)->save($borrowing);
            $reservation->delete();
            return redirect('/pracownik/czytelnicy/' . $request->userId)->with(['success' => 'Książka ' . $item->book->title . ', (egzemplarz ' . $item->book_item_id . ') została zarezerwowana']);
        } else {
            return back()->withErrors(['Egzemplarz jest zarezerwowany przez inną osobę']);
        }
    }


    public function cancelReservation(Request $request) {
        $reservation = Reservation::where('id', $request->id)->firstOrFail();
        $item = $reservation->bookItem;
        $item->update(['status' => BookItem::AVAILABLE]);

        $reservation->delete();
        return response()->json(['success' => 'Rezerwacja została anulowana']);
    }
}
