<?php

namespace App\Http\Controllers\Admin;

use App\Models\Publisher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PublisherController extends Controller {

    public function index() {
        $publishers = Publisher::all();
        return view('/admin/publishers', ['publishers' => $publishers]);
    }

    public function store(Request $request) {
        $exisitingPublisher = Publisher::where('name', $request->name)->get()->first();
        if ($exisitingPublisher) {
            return response()->json(['error' => 'Wydawnictwo ' . $request->name . ' już istnieje'], 409);
        }
        Publisher::create(['name' => $request->name]);
        return response()->json(['success' => 'Wydawnictwo ' . $request->name . ' dodane']);
    }

    public function fetchPublisher($id) {
        $publisher = Publisher::where('id', $id)->with('books')->firstOrFail();
        return view('/admin/publisherInfo', ['publisher' => $publisher]);
    }

    public function update(Request $request, $id) {
        $publisher = Publisher::where('id', '=', $id)->firstOrFail();
        if ($publisher->name != $request->value) {
            $existingPublisher = Publisher::where('name', $request->name)->get();
            if ($existingPublisher->count() > 0) {
                return redirect()->back()->withErrors('Istnieje już wydawnictwo o tej nazwie');
            }
            $publisher->name = $request->value;
        }

        $publisher->save();
        return response()->json(['success' => 'Dane zostały zmienione']);
    }


    public function delete($id) {
        try {
            $publisher = Publisher::where('id', $id)->firstOrFail();
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
