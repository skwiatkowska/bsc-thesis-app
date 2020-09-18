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
}
