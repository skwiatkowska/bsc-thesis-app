<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class HomeController extends Controller {


    public function index() {
        return view('/user/home');
    }

    public function contact() {
        return view('/user/contact');
    }

    public function workingHours() {
        return view('/user/workingHours');
    }

    public function firstSteps() {
        return view('/user/firstSteps');
    }
}
