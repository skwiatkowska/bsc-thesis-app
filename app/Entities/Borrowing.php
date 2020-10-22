<?php

namespace App\Entities;
use Illuminate\Support\Facades\DB;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;
use App\Entities\BookItem;
use App\Entities\User;

class Borrowing extends NeoEloquent {

    protected $label = 'Borrowing';

    protected $fillable = [
        'id',
        'borrow_date',
        'due_date',
        'actual_return_date',
        'was_prolonged',
        'overdue_fee',
        'created_at',
        'updated_at'
    ];


    protected $hidden = [
        'created_at',
        'updated_at'
    ];


    public function bookItem() {
        return $this->morphTo(BookItem::class, 'ON');
    }

    public function user() {
        return $this->belongsTo(User::class, 'BORROWED');
    }
}
