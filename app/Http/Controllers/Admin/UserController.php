<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Entities\User;
use App\Entities\BookItem;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class UserController extends Controller {
    public function createUser() {
        return view('/admin/newUser');
    }

    public function storeUser(Request $request) {
        $user = User::create([
            'first_name' => $request['fname'],
            'last_name' => $request['lname'],
            'pesel' => $request['pesel'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'password' => Hash::make($request['pesel'])
        ]);
        if ($request['isModal'] == 'true') {
            return response()->json(['success' => 'Dodano nowego Czytelnika: ' . $request['fname'] . ' ' . $request['lname']]);
        }
        return redirect('/pracownik/czytelnicy/' . $user->id)->with(['success' => 'Dodano nowego użytkownika: ' . $request['fname'] . ' ' . $request['lname']]);
    }

    public function fetchUser($id) {
        $user = User::where('id', $id)->with('borrowings.bookItem.book.authors')->get()->first();
        return view('/admin/userInfo', ['user' => $user]);
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

    public function findUser(Request $request) {
        if ($request->all()) {

            $searchIn = $request->searchIn;
            $phrase = $request->phrase;
            $searchInMode = null;
            if ($searchIn == "pesel") {
                $users = User::where('pesel', $phrase)->get();
                $searchInMode = "PESEL";
            } elseif ($searchIn == "lname") {
                $users = User::where('last_name', '=~', '.*' . $phrase . '.*')->get();

                $searchInMode = "nazwisko";
            }

            if (!$users->count()) {
                $users = User::all();
                return view('/admin/findUser', ['users' => $users])->withErrors("Nie znaleziono Czytelników spełniających podane kryterium wyszukiwania: " . $phrase . " (" . $searchInMode . ")");
            }
            return view('/admin/findUser', ['users' => $users, 'phrase' => $phrase]);
        } else {
            $users = User::all();
            return view('/admin/findUser', ['users' => $users]);
        }
    }
}
