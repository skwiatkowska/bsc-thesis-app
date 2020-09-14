<?php

namespace App\Http\Controllers;

use App\Entities\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller {



    public function index() {

        $categories = Category::all();
        return view('/librarian/categories', ['categories' => $categories]);
    }

    
    public function store(Request $request) {
        try{
            Category::create([
            'name' => $request->input('name'),
        ]);
    } catch (\Exception $e) {

        return back()->withErrors('Kategoria '.$request->input('name').' już istnieje');
    }

        return back()->with(['success' => 'Utworzono nową kategorię: '.$request->input('name')]);
    }
}
