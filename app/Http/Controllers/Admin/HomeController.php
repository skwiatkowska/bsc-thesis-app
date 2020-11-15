<?php

namespace App\Http\Controllers\Admin;

use App\Models\BookItem;
use App\Models\Borrowing;
use App\Models\Reservation;
use App\Models\User;
use App\Http\Controllers\Controller;

class HomeController extends Controller {
    public function index() {
        $now = new \DateTime();
        $expired = Reservation::with('bookItem')->where('due_date', '<', $now)->get();
        foreach($expired as $exp){
            $item = $exp->bookItem;
            $item->update(['status' => BookItem::AVAILABLE]);
            $exp->delete();
        }

        $borrowings = Borrowing::all()->filter(function ($value) {
            return !isset($value->actual_return_date);
        });

        $reservations = Reservation::all();
        $users = User::all();
        $bookItems = BookItem::all();

        $newBorrowings = Borrowing::all()->filter(function ($value) {
            return !isset($value->actual_return_date) && strtotime($value->created_at) >= strtotime("-7 days");
        });

        $newReservations = Reservation::all()->filter(function ($value) {
            return strtotime($value->created_at) >= strtotime("-7 days");
        });

        $newUsers = User::all()->filter(function ($value) {
            return strtotime($value->created_at) >= strtotime("-7 days");
        });

        $newBookItems = BookItem::all()->filter(function ($value) {
            return strtotime($value->created_at) >= strtotime("-7 days");
        });
        return view('/admin/home', [
            'borrowings' => $borrowings, 'reservations' => $reservations, 'users' => $users, 'bookItems' => $bookItems,
            'newBorrowings' => $newBorrowings, 'newReservations' => $newReservations, 'newUsers' => $newUsers, 'newBookItems' => $newBookItems
        ]);
    }

    public function info() {
        return view('/admin/info');
    }
}
