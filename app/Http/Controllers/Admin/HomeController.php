<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Book;
use App\Entities\BookItem;
use App\Entities\Borrowing;
use App\Entities\Reservation;
use App\Entities\User;
use App\Http\Controllers\Controller;

class HomeController extends Controller {
    public function index() {
        $borrowings = Borrowing::with('user')->with('bookItem')->get();       
        $current = $borrowings->filter(function ($value) {
            return !isset($value->actual_return_date);
        });

        $reservations = Reservation::all();
        $users = User::all();
        $books = Book::all();
        $bookItems = BookItem::all();
        return view('/admin/home', ['borrowings' => $current, 'reservations' => $reservations, 'users' => $users, 'books' => $books, 'bookItems' => $bookItems]);
    }

    public function info() {
        return view('/admin/info');
    }
}
