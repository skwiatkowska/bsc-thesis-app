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
        $book = Book::all()->first();
        $response = $this->get('/ksiazki/'.$book->id);
        $response->assertStatus(200);
        $response->assertViewIs('.user.bookInfo');
        $response->assertSessionHasNoErrors();
        $response->assertViewHas('book');
    }

    /** @test */
    public function bookInfoWrongId() {
        $response = $this->get('/ksiazki/-1');
        $response->assertStatus(404);
        $response->assertNotFound();
    }


    /** @test */
    public function authorInfoCorrectId() {
        $author = Author::create([
            'first_names' => 'test_fname1',
            'last_name' => 'test_lname1',
        ]);
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
        $publisher = Publisher::create([
            'name' => 'testpublisher',
        ]);
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
