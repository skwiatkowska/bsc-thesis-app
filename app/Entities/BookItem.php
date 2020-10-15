<?php

namespace App\Entities;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;
use Vinelab\NeoEloquent\Eloquent\SoftDeletes;

class BookItem extends NeoEloquent {
    use SoftDeletes;

    const AVAILABLE = "Dostępne";
    const RESERVED = "Zarezerwowane";
    const BORROWED = "Wypożyczone";

    
    protected $label = 'BookItem';

    protected $fillable = [
        'id',
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


    protected $dates = ['deleted_at'];

    
    public function book(){
        return $this->belongsTo(Book::class,'HAS_ITEM');
    }

}
