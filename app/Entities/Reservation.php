<?php

namespace App\Entities;
use Illuminate\Support\Facades\DB;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;
use App\Entities\BookItem;
use App\Entities\User;

class Reservation extends NeoEloquent {

    protected $label = 'Reservarion';

    protected $fillable = [
        'id',
        'reservation_date',
        'due_date',
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
        return $this->belongsTo(User::class, 'RESERVED');
    }

}
