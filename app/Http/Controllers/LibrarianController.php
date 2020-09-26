<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\User;

class LibrarianController extends Controller {
    public function index() {
        return view('/librarian/home');
    }


    public function login() {

        return view('/librarian/login');
    }

    public function createUser() {
        return view('/librarian/newMember');
    }

    public function storeUser(Request $request) {
        // dd($request->post());
        $user = User::create([
            'first_name' => $request['fname'],
            'last_name' => $request['lname'],
            'pesel' => $request['pesel'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'password' => bcrypt($request['pesel'])
        ]);
        return redirect('/pracownik/czytelnicy/' . $user->id)->with(['success' => 'Dodano nowego użytkownika: ' .$request['fname']. ' '. $request['lname']]);
    }

    public function fetchUser($id) {
        $user = User::where('id', $id)->get()->first();
        return view('/librarian/userInfo', ['user' => $user]);
    }

    public function updateUser(Request $request, $id) {
        $user = User::where('id', $id)->get()->first();
        if ($request->name == "fname" && $user->first_name != $request->value) {
            $user->first_name = $request->value;
        } else if ($request->name == "lname" && $user->last_name != $request->value) {
            $user->last_name = $request->value;
        }else if ($request->name == "pesel" && $user->pesel != $request->value) {
            $user->pesel = $request->value;
        }else if ($request->name == "phone" && $user->phone != $request->value) {
            $user->phone = $request->value;
        }else if ($request->name == "email" && $user->email != $request->value) {
            $user->email = $request->value;
        }
        $user->save();
        return response()->json(['success' => 'Dane zostały zmienione']);
    }

    public function findUser() {
        return view('/librarian/findUser');
    }

    public function info() {
        return view('/librarian/info');
    }
}
