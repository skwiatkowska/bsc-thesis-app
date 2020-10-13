<?php

namespace App\Entities;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class BookItem extends NeoEloquent {

    const AVAILABLE = 0;
    const RESERVED = 1;
    const BORROWED = 2;

    
    protected $label = 'BookItem';

    protected $fillable = [
        'id',
        'isbn',
        'bookitem_id',
        'status',
        'created_at',
        'updated_at'
    ];


    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function books(){
        return $this->belongsTo(Book::class,'HAS');
    }

}
