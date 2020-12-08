<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller {

    public function index() {
        $categories = Category::all();
 
        return view('/admin/categories', ['categories' => $categories]);
    }


    public function store(Request $request) {
        $exisitingCategory = Category::where('name', $request->name)->get()->first();
        if ($exisitingCategory) {
            return response()->json(['error' => 'Kategoria ' . $request->name . ' już istnieje'], 409);
        }
        Category::create(['name' => $request->name]);
        return response()->json(['success' => 'Kategoria ' . $request->name . ' utworzona']);
    }
}
