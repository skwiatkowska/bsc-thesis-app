<?php

namespace App\Entities;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class User extends NeoEloquent implements Authenticatable {
    use Notifiable, AuthenticableTrait;
    protected $guard = 'web';
    protected $label = 'User';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'pesel',
        'phone',
        'email',
        'password',
        'city',
        'street',
        'zipcode'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at'
    ];
}
