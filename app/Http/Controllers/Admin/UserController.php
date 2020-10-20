<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Entities\User;
use App\Entities\BookItem;
use App\Entities\Borrowing;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use DateTime;

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
        $user = User::where('id', $id)->with('borrows')->get()->first();
        // $borrowed = $user->borrows;
        $item = BookItem::where('id',587)->with('borrows')->get()->first();
        dd($item);
        foreach($user->borrows as $borrow){
            $b = Borrowing::where('id', $borrow->id)->with('borrows')->get();
            dd($b);
        }
        // dd($borrowed->first()->with('borrowable')->get());
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

    public function findUserView() {
        $users = User::all();
        return view('/admin/findUser', ['users' => $users]);
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
        return view('/admin/findUser', ['users' => $users, 'phrase' => $phrase]);
    }

    public function borrowBookItemAddUser($id) {
        $item = BookItem::with('book')->where('id', $id)->get()->first();
        $book = $item->book::with('authors')->get()->first();
        return view('/admin/addUserToBorrowing', ['item' => $item, 'book' => $book,'users' => '']);
    }



    public function borrowBookItemFindUser(Request $request, $id) {
        $searchIn = $request->searchIn;
        $phrase = $request->phrase;
        if ($searchIn == "pesel") {
            $users = User::where('pesel', $phrase)->get();
        } elseif ($searchIn == "lname") {
            $users = User::where('last_name', '=~', '.*' . $phrase . '.*')->get();
            if (!$users->count()) {
                // return redirect('/pracownik/katalog')->withErrors("Nie znaleziono takiego Czytelnika: " . $phrase);
            }
        }
        $item = BookItem::with('book')->where('id', $id)->get()->first();
        $book = $item->book::with('authors')->get()->first();
        return view('/admin/addUserToBorrowing', ['item' => $item,'users' => $users, 'book' => $book, 'phrase' => $phrase]);
    }

    public function borrowBook(Request $request) {
        $user = User::where('id',$request->userId)->get()->first();
        $item = BookItem::with('book')->where('id',$request->bookItemId)->get()->first();
        $borrowing = new Borrowing(['borrow_date' => new DateTime(), 'due_date' => new DateTime("+1 month"), 'was_prolonged' => false]);

        $user->borrows($item)->save($borrowing);       
        return redirect('/pracownik/czytelnicy/' . $request->userId)->with(['success' => 'Książka '.$item->book->title.' została wypożyczona']);
    }

}
