<?php

namespace App\Http\Controllers;

use App\Entities\Publisher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PublisherController extends Controller {



    public function index() {
        $publishers = Publisher::all();
        return view('/librarian/publishers', ['publishers' => $publishers]);
    }

    public function store(Request $request) {
        try {
            Publisher::create([
                'name' => $request->name,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Wydawnictwo ' . $request->name . ' już istnieje'], 409);
        }

        return response()->json(['success' => 'Wydawnictwo ' . $request->name . ' dodane']);
    }

    public function fetchPublisher($id) {
        $publisher = Publisher::where('id', $id)->with('books')->get()->first();
        return view('/librarian/publisherInfo', ['publisher' => $publisher]);
    }

    public function update(Request $request, $id) {
        $publisher = Publisher::where('id', $id)->get()->first();
        if ($publisher->name != $request->value) {
            $publisher->name = $request->value;
        }

        $publisher->save();
        return response()->json(['success' => 'Dane zostały zmienione']);
    }


    public function delete($id) {
        try {
            $publisher = Publisher::where('id', $id)->get()->first();
            $this->checkIfHasAssignedBooks($publisher);
            $publisher->delete();
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
        return redirect('/pracownik/wydawnictwa')->with(['success' => "Wydawnictwo " . $publisher->name . "zostało usunięte"]);
    }

    public function checkIfHasAssignedBooks($publisher) {
        $numberOfBooks = $publisher->books()->count();
        if ($numberOfBooks > 0) {
            throw new \Exception("Nie można usunąć wydawnictwa z przypisanymi książkami");
        }
    }
}
