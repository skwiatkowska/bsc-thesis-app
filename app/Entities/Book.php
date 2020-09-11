<?php

namespace App\Entities;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;
use App\Entities\Category;
use App\Entities\Author;

class Book extends NeoEloquent{
    protected $label = 'Book';

    protected $fillable = [
        'id',
        'title',
        'ISBN',
        'pages_number',
        'publisher',
        'publication_year',
        'book_items_number',
        'created_at',
        'updated_at'
    ];


    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function category(){
        return $this->belongsTo(Category::class,'BELONGS_TO');
    }

    public function author(){
        return $this->hasMany(Author::class,'WRITTEN_BY');
    }
}