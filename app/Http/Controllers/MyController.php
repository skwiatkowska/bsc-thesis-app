<?php

namespace App\Http\Controllers;

use App\Entities\Book;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MyController extends Controller{
   
    
    public function index(){
        $book = Book::create(['title' => 'aa']);

            return view('welcome');
        }
    
}
