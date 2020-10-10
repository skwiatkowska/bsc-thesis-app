<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller {



    public function index() {
        $categories = Category::all();
        return view('/librarian/categories', ['categories' => $categories]);
    }


    public function store(Request $request) {
        try {
            Category::create([
                'name' => $request->input('name'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Kategoria ' . $request->input('name') . ' już istnieje'], 409);
        }

        return response()->json(['success' => 'Kategoria ' . $request->input('name') . ' utworzona']);
    }
}
