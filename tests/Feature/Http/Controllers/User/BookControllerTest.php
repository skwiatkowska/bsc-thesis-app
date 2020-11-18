<?php

namespace Tests\Feature\Http\Controllers\User;

use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookControllerTest extends TestCase {    
    /** @test */
    public function userBooksViewAfterSuccessfullyLogin() {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $response = $this->post('/logowanie', [
            'email' => $user->email,
            'password' => bcrypt('password')
        ]);
        $response->assertStatus(302);
        $this->assertAuthenticatedAs($user);
        $response = $this->get('/moje-ksiazki');
        $response->assertStatus(200);
        $response->assertViewIs('.user.userBooks');
        $response->assertSessionHasNoErrors();
        $response->assertViewHas('user');
        $user->delete();
    }

    /** @test */
    public function userBooksViewUnauthenticated() {
        $response = $this->get('/moje-ksiazki');
        $response->assertRedirect('/logowanie');
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }


    /** @test */
    public function bookInfoCorrectId() {
        $book = factory(Book::class)->create();
        $author = factory(Author::class)->create();
        $publisher = factory(Publisher::class)->create();
        $publisher->books()->save($book);
        $author->books()->save($book);
        $response = $this->get('/ksiazki/'.$book->id);
        $response->assertStatus(200);
        $response->assertViewIs('.user.bookInfo');
        $response->assertSessionHasNoErrors();
        $response->assertViewHas('book');
        $author->delete();
        $publisher->delete();
        $book->delete();
    }

    /** @test */
    public function bookInfoWrongId() {
        $response = $this->get('/ksiazki/-1');
        $response->assertStatus(404);
        $response->assertNotFound();
    }


    /** @test */
    public function authorInfoCorrectId() {
        $author = factory(Author::class)->create();
        $response = $this->get('/autorzy/'.$author->id);
        $response->assertStatus(200);
        $response->assertViewIs('.user.authorInfo');
        $response->assertSessionHasNoErrors();
        $response->assertViewHas('author');
        $author->delete();
    }


    /** @test */
    public function authorInfoWrongId() {
        $response = $this->get('/autorzy/-1');
        $response->assertStatus(404);
        $response->assertNotFound();
    }


    /** @test */
    public function publisherInfoCorrectId() {
        $publisher = factory(Publisher::class)->create();
        $response = $this->get('/wydawnictwa/'.$publisher->id);
        $response->assertStatus(200);
        $response->assertViewIs('.user.publisherInfo');
        $response->assertSessionHasNoErrors();
        $response->assertViewHas('publisher');
        $publisher->delete();
    }

    /** @test */
    public function publisherInfoWrongId() {
        $response = $this->get('/wydawnictwa/-1');
        $response->assertStatus(404);
        $response->assertNotFound();
    }


    //prolongBookItem + find
}
