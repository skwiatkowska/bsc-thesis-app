<?php

namespace App\Http\Controllers\User;

use App\Entities\BookItem;
use App\Entities\Reservation;
use App\Entities\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use DateTime;

class ReservationController extends Controller {

    public function index() {
        // 
    }

    public function reserveBook(Request $request){
        $user = Auth::user();
        $item = BookItem::with('book')->with('borrowings')->where('id', $request->bookItemId)->get()->first();
        if ($item->status != BookItem::AVAILABLE || $item->is_blocked) {
            return back()->withErrors("Ten egzemplarz jest już wypożyczony lub niedostępny");
        }
    
        $reservation = new Reservation(['reservation_date' => new DateTime(), 'due_date' => new DateTime("+3 days")]);
        $item->update(['status' => BookItem::RESERVED]);
        $user->reservations($item)->save($reservation);
        // return redirect('/pracownik/czytelnicy/' . $request->userId)->with(['success' => 'Książka ' . $item->book->title . ' została wypożyczona']);
     
    }
}
