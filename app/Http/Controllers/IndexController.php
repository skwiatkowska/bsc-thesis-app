<?php

namespace App\Http\Controllers;

use App\Entities\Book;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller {


    public function index() {
        return view('home');
    }


    public function contact() {
        return view('contact');
    }

    public function workingHours() {
        return view('workingHours');
    }

    public function firstSteps() {
        return view('firstSteps');
    }
}
