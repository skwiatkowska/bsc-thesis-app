<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LibrarianController extends Controller {
    public function index() {
        return view('/librarian/home');
    }


    public function login() {

        return view('/librarian/login');
    }

    public function registerMember() {
        return view('/librarian/newMember');
    }
}
