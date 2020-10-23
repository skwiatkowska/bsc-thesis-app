<?php

namespace App\Http\Controllers\Admin;

use App\Entities\BookItem;
use App\Entities\Borrowing;
use App\Http\Controllers\Controller;

class HomeController extends Controller {
    public function index() {
        $borrowings = Borrowing::with('user')->with('bookItem')->get();
        $current = collect();
        foreach($borrowings as $borrowing){
            if($borrowing->bookItem->status == BookItem::BORROWED){
                $current->push($borrowing);
            }
        }
        // dd($current);
        // $current =  json_encode($current);
        return view('/admin/home', ['borrowings' => $borrowings]);
    }

    public function info() {
        return view('/admin/info');
    }
}
