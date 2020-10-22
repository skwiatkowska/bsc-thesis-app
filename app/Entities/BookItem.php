<?php

namespace App\Entities;

use Illuminate\Support\Facades\DB;
use App\Entities\Borrowing;
use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class BookItem extends NeoEloquent {

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



    public function book() {
        return $this->belongsTo(Book::class, 'HAS_ITEM');
    }

    public function borrowings() {
        return $this->morphMany(Borrowing::class, 'ON');
    }

    public function deleteRelatedBorrowing($borrowingId) {
        $cypher = "MATCH (b:Borrowing)-[rel1:ON]->(item:BookItem) WHERE ID(b)=$borrowingId AND ID(item)=$this->id 
                    MATCH (b:Borrowing)<-[rel2:BORROWED]-(u:User) WHERE ID(b)=$borrowingId
                    DELETE rel1, rel2;";
        return DB::select($cypher);
    }
}
