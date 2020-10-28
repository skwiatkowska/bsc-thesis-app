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

    public function editProfile() {
        $user = Auth::user();
        return view('user/editProfile', ['user' => $user]);
    }

    public function updateProfile(Request $request) {
        $user = Auth::user();        
        if ($user->first_name != $request->first_name) {
            $user->first_name = $request->first_name;
        }
        if ($user->last_name != $request->last_name) {
            $user->last_name = $request->last_name;
        }
        if ($user->pesel != $request->pesel) {
            $user->pesel = $request->pesel;
        }
        if ($user->phone != $request->phone) {
            $user->phone = $request->phone;
        }
        if ($user->street != $request->street) {
            $user->street = $request->street;
        }
        if ($user->house_number != $request->house_number) {
            $user->house_number = $request->house_number;
        }
        if ($user->zipcode != $request->zipcode) {
            $user->zipcode = $request->zipcode;
        }
        if ($user->city != $request->city) {
            $user->city = $request->city;
        }
       

       
        $user->save();
        return redirect("/dane")->with(['success' => 'Twoje dane zostały zaktualizowane']);
    }

}
