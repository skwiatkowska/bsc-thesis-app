<?php

namespace App\Entities;

use Illuminate\Support\Facades\DB;
use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;
use App\Entities\Category;
use App\Entities\Author;
use App\Entities\Publisher;

class Book extends NeoEloquent {

    protected $label = 'Book';

    protected $fillable = [
        'id',
        'title',
        'isbn',
        'pages_number',
        'publication_year',
        'book_items_number',
        'created_at',
        'updated_at'
    ];


    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function categories() {
        return $this->belongsToMany(Category::class, 'CONSISTS_OF');
    }

    public function authors() {
        return $this->belongsToMany(Author::class, 'WROTE');
    }

    public function publisher() {
        return $this->belongsTo(Publisher::class, 'PUBLISHED');
    }

    public function deleteRelatedCategory($categoryId) {
        $cypher = "MATCH (cat:Category)-[c:CONSISTS_OF]->(book:Book) WHERE ID(cat)=$categoryId AND ID(book)=$this->id DELETE c;";
        return DB::select($cypher);
    }

    public function deleteRelatedAuthor($authorId) {
        $cypher = "MATCH (author:Author)-[w:WROTE]->(book:Book) WHERE ID(author)=$authorId AND ID(book)=$this->id DELETE w;";
        return DB::select($cypher);
    }

    public function deleteRelatedPublisher($publisherId) {
        $cypher = "MATCH (publisher:Publisher)-[p:PUBLISHED]->(book:Book) WHERE ID(publisher)=$publisherId AND ID(book)=$this->id DELETE p;";
        return DB::select($cypher);
    }
}
