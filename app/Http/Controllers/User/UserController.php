<?php

namespace App\Http\Controllers\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class UserController extends Controller {


    public function userInfo() {
        $user = Auth::user();
        return view('user/userInfo', ['user' => $user]);
    }

}
