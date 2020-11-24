<?php

namespace App\Http\Controllers\User;

use App\Models\BookItem;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use DateTime;

class ReservationController extends Controller {

    public function reserveBook(Request $request) {
        $user = Auth::user();
        $item = BookItem::with('book')->with('borrowings')->where('id', $request->bookItemId)->firstOrFail();
        if ($item->status != BookItem::AVAILABLE || $item->is_blocked) {
            return back()->withErrors("Ten egzemplarz jest już wypożyczony lub niedostępny");
        }
        $dueDate = new DateTime("+3 days");
        if (date('w', strtotime("+3 days")) == 0) { //sunday
            $dueDate = new DateTime("+4 days");
        } else if (date('w', strtotime("+3 days")) == 6) { //saturday
            $dueDate = new DateTime("+5 days");
        }
        $reservation = new Reservation(['reservation_date' => new DateTime(), 'due_date' => $dueDate]);
        $item->update(['status' => BookItem::RESERVED]);
        $user->reservations($item)->save($reservation);
        return redirect('/moje-ksiazki')->with(['success' => 'Książka ' . $item->book->title . ' została wypożyczona']);
    }


    public function cancelReservation(Request $request) {
        $reservation = Reservation::where('id', $request->id)->firstOrFail();
        if ($reservation->user->id == Auth::user()->id) {
            $item = $reservation->bookItem;
            $item->update(['status' => BookItem::AVAILABLE]);

            $reservation->delete();
            return response()->json(['success' => 'Rezerwacja została anulowana']);
        } else {
            return response()->json(['error' => 'Nie znaleziono rezerwacji przypisanej do Twojego konta'], 403);
        }
    }
}
