<?php

namespace App\Entities;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;
use App\Entities\Category;
use App\Entities\Author;
use App\Entities\Publisher;

class Book extends NeoEloquent{

    protected $label = 'Book';

    protected $fillable = [
        'id',
        'title',
        'isbn',
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

    public function categories(){
        return $this->belongsToMany(Category::class,'CONSISTS_OF');
    }

    public function authors(){
        return $this->belongsToMany(Author::class,'WROTE');
    }

    public function publisher(){
        return $this->belongsTo(Publisher::class,'PUBLISHED');
    }
}