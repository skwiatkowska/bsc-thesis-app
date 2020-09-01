<?php

namespace App\Entities;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class Book extends NeoEloquent{
    protected $label = 'Book';

    protected $fillable = ['title'];
}
