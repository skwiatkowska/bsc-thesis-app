<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Author;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorControllerTest extends TestCase {
    public function logIn() {
        $admin = factory(Admin::class)->create();
        $this->actingAs($admin);
        $response = $this->post('/pracownik/logowanie', [
            'email' => $admin->email,
            'password' => 'password'
        ]);
        $response->assertStatus(302);
        // $this->assertAuthenticatedAs($admin);
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
        $data = array(
            'fname' => 'test123',
            'lname' => 'test123',
        );
        $response = $this->post('/pracownik/autorzy', $data);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $authorsAfter = Author::all()->count();
        $this->assertGreaterThan($authorsBefore, $authorsAfter);
        $author = Author::where('first_names', 'test123')->where('last_name', 'test123')->get()->first();
        $author->delete();
        $admin->delete();
    }

    /** @test */
    public function updateAuthorSuccess() {
        $admin = $this->logIn();
        $author = Author::create([
            'first_names' => 'test_test12345',
            'last_name' => 'test_test12345',
        ]);
        
        $newName = 'new_name123';
        $response = $this->post('/pracownik/autorzy/'.$author->id.'/edycja', [
            'name' => 'fname',
            'value' => $newName
        ]);
        $authorUpdated = Author::where('id', $author->id)->firstOrFail();
        $this->assertEquals($authorUpdated->first_names, $newName);
        $response->assertSessionHasNoErrors();
        $author->delete();
        $admin->delete();      
    }

    /** @test */
    public function authorInfoCorrectId() {
        $admin = $this->logIn();
        $author = Author::create([
            'first_names' => 'test_fname111',
            'last_name' => 'test_lname111',
        ]);
        $response = $this->get('/pracownik/autorzy/'.$author->id);
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



    ////delete
}
