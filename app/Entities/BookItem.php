<?php

namespace App\Entities;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class BookItem extends NeoEloquent {

    const AVAILABLE = "Dostępne";
    const RESERVED = "Zarezerwowane";
    const BORROWED = "Wypożyczone";

    
    protected $label = 'BookItem';

    protected $fillable = [
        'id',
        'isbn',
        'bookitem_id',
        'status',
        'is_blocked',
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
