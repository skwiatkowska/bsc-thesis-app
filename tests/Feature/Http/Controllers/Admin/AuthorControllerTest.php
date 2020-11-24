<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Author;
use App\Models\Book;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorControllerTest extends TestCase {
    use WithFaker;

    public function logIn() {
        $admin = factory(Admin::class)->create();
        $this->actingAs($admin);
        $response = $this->post('/pracownik/logowanie', [
            'email' => $admin->email,
            'password' => 'password'
        ]);
        $response->assertStatus(302);
        return $admin;
    }

    /** @test */
    public function authorsList() {
        $admin = $this->logIn();
        $response = $this->get('/pracownik/autorzy');
        $response->assertViewIs('.admin.authors');
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewHas('authors');
        $admin->delete();
    }

    /** @test */
    public function createNewAuthorSuccess() {
        $admin = $this->logIn();
        $authorsBefore = Author::all()->count();
        $fname = $this->faker->unique()->firstName;
        $lname = $this->faker->unique()->lastName;

        $data = array(
            'fname' => $fname,
            'lname' => $lname,
        );
        $response = $this->post('/pracownik/autorzy', $data);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $content);
        $authorsAfter = Author::all()->count();
        $this->assertGreaterThan($authorsBefore, $authorsAfter);
        $author = Author::where('first_names', $fname)->where('last_name', $lname)->get()->first();

        $author->delete();
        $admin->delete();
    }

    /** @test */
    public function updateAuthorSuccess() {
        $admin = $this->logIn();
        $author = factory(Author::class)->create();
        $newName = $this->faker->unique()->name;

        $response = $this->post('/pracownik/autorzy/' . $author->id . '/edycja', [
            'name' => 'fname',
            'value' => $newName
        ]);
        $authorUpdated = Author::where('id', $author->id)->firstOrFail();
        $this->assertEquals($authorUpdated->first_names, $newName);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $content);

        $author->delete();
        $admin->delete();
    }

    /** @test */
    public function authorInfoCorrectId() {
        $admin = $this->logIn();
        $author = factory(Author::class)->create();

        $response = $this->get('/pracownik/autorzy/' . $author->id);
        $response->assertStatus(200);
        $response->assertViewIs('.admin.authorInfo');
        $response->assertSessionHasNoErrors();
        $response->assertViewHas('author');

        $author->delete();
        $admin->delete();
    }


    /** @test */
    public function authorInfoWrongId() {
        $admin = $this->logIn();
        $response = $this->get('/pracownik/autorzy/-1');
        $response->assertStatus(404);
        $response->assertNotFound();
        $admin->delete();
    }

    /** @test */
    public function deleteAuthorWithoutBooks() {
        $admin = $this->logIn();
        $author = factory(Author::class)->create();
        $this->assertEquals($author->books->count(), 0);

        $response = $this->post('/pracownik/autorzy/' . $author->id . '/usun', []);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/pracownik/autorzy');
        $authorAfter = Author::where('id', $author->id)->get();
        $this->assertEquals($authorAfter->count(), 0);

        $admin->delete();
    }

    /** @test */
    public function deleteAuthorWithBooks() {
        $admin = $this->logIn();
        $author = factory(Author::class)->create();
        $book = factory(Book::class)->create();
        $author->books()->save($book);

        $this->assertGreaterThan(0, $author->books->count());
        $response = $this->post('/pracownik/autorzy/' . $author->id . '/usun', []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $authorAfter = Author::where('id', $author->id)->get();
        $this->assertNotEquals($authorAfter->count(), 0);

        $author->delete();
        $book->delete();
        $admin->delete();
    }
}
