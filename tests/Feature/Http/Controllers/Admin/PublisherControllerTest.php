<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Book;
use App\Models\Publisher;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PublisherControllerTest extends TestCase {
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
    public function publishersList() {
        $admin = $this->logIn();
        $response = $this->get('/pracownik/wydawnictwa');
        $response->assertViewIs('.admin.publishers');
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewHas('publishers');
        $admin->delete();
    }

    /** @test */
    public function createNewPublisherSuccess() {
        $admin = $this->logIn();
        $publishersBefore = Publisher::all()->count();
        $newName = $this->faker->unique()->name;
        $response = $this->post('/pracownik/wydawnictwa', ['name' => $newName]);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $publishersAfter = Publisher::all()->count();
        $this->assertGreaterThan($publishersBefore, $publishersAfter);
        $publisher = Publisher::where('name', $newName)->get()->first();
        $publisher->delete();
        $admin->delete();
    }

    /** @test */
    public function createNewPublisherDuplicatedError() {
        $admin = $this->logIn();
        $anotherPublisher = factory(Publisher::class)->create();
        $publishersBefore = Publisher::all()->count();
        $response = $this->post('/pracownik/wydawnictwa', ['name' => $anotherPublisher->name]);
        $response->assertStatus(409);
        $publishersAfter = Publisher::all()->count();
        $this->assertEquals($publishersBefore, $publishersAfter);
        $anotherPublisher->delete();
        $admin->delete();
    }

    /** @test */
    public function updatePublisherSuccess() {
        $admin = $this->logIn();
        $publisher = factory(Publisher::class)->create();
        $newName = $this->faker->unique()->name;
        $response = $this->post('/pracownik/wydawnictwa/' . $publisher->id . '/edycja', [
            'value' => $newName
        ]);
        $publisherUpdated = Publisher::where('id', $publisher->id)->firstOrFail();

        $this->assertEquals($publisherUpdated->name, $newName);
        $response->assertSessionHasNoErrors();
        // $publisher->delete();
        $admin->delete();
    }

    /** @test */
    public function publisherInfoCorrectId() {
        $admin = $this->logIn();
        $publisher = factory(Publisher::class)->create();
        $response = $this->get('/pracownik/wydawnictwa/' . $publisher->id);
        $response->assertStatus(200);
        $response->assertViewIs('.admin.publisherInfo');
        $response->assertSessionHasNoErrors();
        $response->assertViewHas('publisher');
        $publisher->delete();
        $admin->delete();
    }


    /** @test */
    public function publisherInfoWrongId() {
        $admin = $this->logIn();
        $response = $this->get('/pracownik/wydawnictwa/-1');
        $response->assertStatus(404);
        $response->assertNotFound();
        $admin->delete();
    }

    /** @test */
    public function deletePublisherWithoutBooks() {
        $admin = $this->logIn();
        $publisher = factory(Publisher::class)->create();
        $this->assertEquals($publisher->books->count(), 0);
        $response = $this->post('/pracownik/wydawnictwa/' . $publisher->id . '/usun', []);
        $response->assertStatus(302);
        $response->assertRedirect('/pracownik/wydawnictwa');
        $publisherAfter = Publisher::where('id', $publisher->id)->get();
        $this->assertEquals($publisherAfter->count(), 0);
        $admin->delete();
    }

    /** @test */
    public function deletePublisherWithBooks() {
        $admin = $this->logIn();
        $publisher = factory(Publisher::class)->create();
        $book = factory(Book::class)->create();
        $publisher->books()->save($book);
        $this->assertGreaterThan(0, $publisher->books->count());
        $response = $this->post('/pracownik/wydawnictwa/' . $publisher->id . '/usun', []);

        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $publisherAfter = Publisher::where('id', $publisher->id)->get();
        $this->assertNotEquals($publisherAfter->count(), 0);
        $publisher->delete();
        $book->delete();
    }
}
