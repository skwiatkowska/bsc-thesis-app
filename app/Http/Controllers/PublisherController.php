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

    public function fetchPublisher($id){
        $publisher = Publisher::where('id', $id)->with('books')->get()->first();
        return view('/librarian/publisherInfo', ['publisher' => $publisher]);

    }
}
