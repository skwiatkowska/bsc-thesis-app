<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Entities\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class UserController extends Controller {
    public function createUser() {
        return view('/librarian/newUser');
    }

    public function storeUser(Request $request) {
        // dd($request->post());
        $user = User::create([
            'first_name' => $request['fname'],
            'last_name' => $request['lname'],
            'pesel' => $request['pesel'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'password' => Hash::make($request['pesel'])
        ]);
        return redirect('/pracownik/czytelnicy/' . $user->id)->with(['success' => 'Dodano nowego użytkownika: ' . $request['fname'] . ' ' . $request['lname']]);
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
        } else if ($request->name == "pesel" && $user->pesel != $request->value) {
            $user->pesel = $request->value;
        } else if ($request->name == "phone" && $user->phone != $request->value) {
            $user->phone = $request->value;
        } else if ($request->name == "email" && $user->email != $request->value) {
            $user->email = $request->value;
        }
        $user->save();
        return response()->json(['success' => 'Dane zostały zmienione']);
    }

    public function findUserView() {
        $users = User::all();
        return view('/librarian/findUser', ['users' => $users]);
    }

    public function findUser(Request $request) {
        $searchIn = $request->searchIn;
        $phrase = $request->phrase;
        $searchInMode = null;
        if ($searchIn == "pesel") {
            $users = User::where('pesel', $phrase)->get();
            $searchInMode = "PESEL";
        } elseif ($searchIn == "lname") {
            $users = User::where('last_name', '=~', '.*' . $phrase . '.*')->get();
            if (!$users->count()) {
                return redirect('/pracownik/katalog')->withErrors("Nie znaleziono takiego Czytelnika: " . $phrase);
            }
            $searchInMode = "nazwisko";
        }

        if (!$users->count()) {
            return redirect('/pracownik/czytelnicy/znajdz')->withErrors("Nie znaleziono Czytelników spełniających podane kryterium wyszukiwania: " . $phrase . " (" . $searchInMode . ")");
        }
        return view('/librarian/findUser', ['users' => $users, 'phrase' => $phrase]);
    }



}
