<?php

namespace App\Http\Controllers\Admin;

use App\Entities\BookItem;
use App\Entities\Reservation;
use App\Entities\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DateTime;

class ReservationController extends Controller {

    public function index() {
        $reservations = Reservation::with('user')->with('bookItem.book')->get();
        return view('/admin/reservations', ['reservations' => $reservations]);
    }
}
