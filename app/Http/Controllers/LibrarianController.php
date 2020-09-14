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

    public function createMember() {
        return view('/librarian/newMember');
    }

    public function storeMember() {
        return view('/librarian/newMember');
    }


    public function findMember() {
        return view('/librarian/findMember');
    }

    public function info() {
        return view('/librarian/info');
    }
}
