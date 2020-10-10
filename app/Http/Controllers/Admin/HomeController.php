<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

class HomeController extends Controller {
    public function index() {
        return view('/librarian/home');
    }

    public function info() {
        return view('/librarian/info');
    }
}
