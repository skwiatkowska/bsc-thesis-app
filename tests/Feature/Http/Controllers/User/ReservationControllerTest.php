<?php

namespace Tests\Feature\Http\Controllers\User;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookItem;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\Reservation;
use App\Models\User;
use DateTime;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationControllerTest extends TestCase {

    public function logIn() {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $response = $this->post('/logowanie', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertStatus(302);
        $this->assertAuthenticatedAs($user);
        return $user;
    }

    /** @test */
    public function cancelReservationSuccess() {
        $user = $this->logIn();
        $book = factory(Book::class)->create();
        $bookItem = factory(BookItem::class)->create();
        $author = factory(Author::class)->create();
        $publisher = factory(Publisher::class)->create();
        $category = factory(Category::class)->create();

        $publisher->books()->save($book);
        $author->books()->save($book);
        $category->books()->save($book);
        $book->bookItems()->save($bookItem);

        $reservation =  new Reservation(['due_date' =>  new DateTime("+3 days")]);
        $user->reservations($bookItem)->save($reservation);

        $response = $this->delete('/anuluj-rezerwacje', ['id' => $reservation->id]);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $content);

        $author->delete();
        $publisher->delete();
        $category->delete();
        $bookItem->delete();
        $book->delete();
        $user->delete();
    }


    /** @test */
    public function cancelReservationForAnotherUser() {
        $user = $this->logIn();
        $book = factory(Book::class)->create();
        $bookItem = factory(BookItem::class)->create();
        $author = factory(Author::class)->create();
        $publisher = factory(Publisher::class)->create();
        $category = factory(Category::class)->create();
        $user2 = factory(User::class)->create();

        $publisher->books()->save($book);
        $author->books()->save($book);
        $category->books()->save($book);
        $book->bookItems()->save($bookItem);

        $reservation =  new Reservation(['due_date' =>  new DateTime("+3 days")]);
        $user2->reservations($bookItem)->save($reservation);

        $response = $this->delete('/anuluj-rezerwacje', ['id' => $reservation->id]);
        $response->assertStatus(403);
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $content);

        $author->delete();
        $publisher->delete();
        $category->delete();
        $bookItem->delete();
        $book->delete();
        $reservation->delete();
        $user->delete();
        $user2->delete();
    }


    /** @test */
    public function reserveBookSuccess() {
        $user = $this->logIn();
        $book = factory(Book::class)->create();
        $bookItem = factory(BookItem::class)->create();
        $author = factory(Author::class)->create();
        $publisher = factory(Publisher::class)->create();
        $category = factory(Category::class)->create();

        $publisher->books()->save($book);
        $author->books()->save($book);
        $category->books()->save($book);
        $book->bookItems()->save($bookItem);

        $response = $this->post('/zarezerwuj', ['bookItemId' => $bookItem->id]);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/moje-ksiazki');
        $this->assertGreaterThan(0, $user->reservations->count());

        $reservation = $user->reservations->first();
        $author->delete();
        $publisher->delete();
        $category->delete();
        $bookItem->delete();
        $reservation->delete();
        $book->delete();
        $user->delete();
    }


    /** @test */
    public function reserveBookNotAvailableError() {
        $user = $this->logIn();
        $book = factory(Book::class)->create();
        $bookItem = factory(BookItem::class)->create();
        $author = factory(Author::class)->create();
        $publisher = factory(Publisher::class)->create();
        $category = factory(Category::class)->create();

        $publisher->books()->save($book);
        $author->books()->save($book);
        $category->books()->save($book);
        $book->bookItems()->save($bookItem);
        $bookItem->update(['status' => BookItem::RESERVED]);

        $response = $this->post('/zarezerwuj', ['bookItemId' => $bookItem->id]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $this->assertEquals($user->reservations->count(), 0);

        $author->delete();
        $publisher->delete();
        $category->delete();
        $bookItem->delete();
        $book->delete();
        $user->delete();
    }
}
