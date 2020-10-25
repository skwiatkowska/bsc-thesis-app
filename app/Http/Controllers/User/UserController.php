<?php

namespace App\Http\Controllers\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Entities\BookItem;
use App\Entities\Reservation;
use App\Entities\User;
use DateTime;
use Illuminate\Http\Request;

class UserController extends Controller {


    public function userInfo(Request $request) {
        $user = Auth::user();

        // $item = BookItem::where('id',1248)->with('book')->with('borrowings')->get()->first();
        // if ($item->status != BookItem::AVAILABLE || $item->is_blocked) {
        //     return back()->withErrors("Ten egzemplarz jest już wypożyczony lub niedostępny");
        // }
    
        // $reservation = new Reservation(['reservation_date' => new DateTime(), 'due_date' => new DateTime("+3 days")]);
        // $item->update(['status' => BookItem::RESERVED]);
        // $user->reservations($item)->save($reservation);
        return view('user/userInfo', ['user' => $user]);
    }

}
