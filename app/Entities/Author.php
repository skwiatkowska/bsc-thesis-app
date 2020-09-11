<?php

namespace App\Entities;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;
use App\Entities\Book;


class Author extends NeoEloquent {
    protected $label = 'Author';

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'created_at',
        'updated_at'
    ];


    protected $hidden = [
        'created_at',
        'updated_at'
    ];

}
