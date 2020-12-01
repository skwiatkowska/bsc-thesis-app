<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookItem;
use App\Models\Borrowing;
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
    public function reservationList() {
        $admin = $this->logIn();
        $response = $this->get('/pracownik/rezerwacje');
        $response->assertViewIs('.admin.reservations');
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewHas('reservations');
        $admin->delete();
    }

    /** @test */
    public function cancelReservationSuccess() {
        $admin = $this->logIn();
        $book = factory(Book::class)->create();
        $bookItem = factory(BookItem::class)->create();
        $author = factory(Author::class)->create();
        $publisher = factory(Publisher::class)->create();
        $category = factory(Category::class)->create();
        $user = factory(User::class)->create();
        $publisher->books()->save($book);
        $author->books()->save($book);
        $category->books()->save($book);
        $book->bookItems()->save($bookItem);
        $reservation =  new Reservation(['due_date' =>  new DateTime("+3 days")]);
        $user->reservations($bookItem)->save($reservation);

        $response = $this->post('/pracownik/rezerwacje/anuluj', ['id' => $reservation->id]);
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
        $admin->delete();
    }


    /** @test */
    public function borrowBookSuccess() {
        $admin = $this->logIn();
        $book = factory(Book::class)->create();
        $bookItem = factory(BookItem::class)->create();
        $author = factory(Author::class)->create();
        $publisher = factory(Publisher::class)->create();
        $category = factory(Category::class)->create();
        $user = factory(User::class)->create();
        $publisher->books()->save($book);
        $author->books()->save($book);
        $category->books()->save($book);
        $book->bookItems()->save($bookItem);

        $reservation =  new Reservation(['due_date' =>  new DateTime("+3 days")]);
        $user->reservations($bookItem)->save($reservation);
        $reservationsBefore = $user->reservations()->count();
        $borrowingsBefore = $user->borrowings()->count();

        $data = array(
            'userId' => $user->id,
            'bookItemId' => $bookItem->id,
            'reservationId' => $reservation->id
        );

        $response = $this->post('/pracownik/egzemplarze/' . $bookItem->id . '/rezerwacja/wypozycz', $data);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/pracownik/czytelnicy/' . $user->id);

        $reservationsAfter = $user->reservations()->count();
        $borrowingsAfter = $user->borrowings()->count();
        $this->assertLessThan($reservationsBefore, $reservationsAfter);
        $this->assertGreaterThan($borrowingsBefore, $borrowingsAfter);
        $borrowing = $user->borrowings()->first();

        $author->delete();
        $publisher->delete();
        $category->delete();
        $bookItem->delete();
        $book->delete();
        // $reservation->delete();
        $borrowing->delete();
        $user->delete();
        $admin->delete();
    }


    /** @test */
    public function borrowBookNotAvailableError() {
        $admin = $this->logIn();
        $book = factory(Book::class)->create();
        $bookItem = factory(BookItem::class)->create();
        $author = factory(Author::class)->create();
        $publisher = factory(Publisher::class)->create();
        $category = factory(Category::class)->create();
        $user = factory(User::class)->create();
        $publisher->books()->save($book);
        $author->books()->save($book);
        $category->books()->save($book);
        $book->bookItems()->save($bookItem);

        $reservation =  new Reservation(['due_date' =>  new DateTime("+3 days")]);
        $user->reservations($bookItem)->save($reservation);
        $bookItem->update(['status' => BookItem::BORROWED]);
        $reservationsBefore = $user->reservations()->count();
        $borrowingsBefore = $user->borrowings()->count();

        $data = array(
            'userId' => $user->id,
            'bookItemId' => $bookItem->id,
            'reservationId' => $reservation->id
        );

        $response = $this->post('/pracownik/egzemplarze/' . $bookItem->id . '/rezerwacja/wypozycz', $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $reservationsAfter = $user->reservations()->count();
        $borrowingsAfter = $user->borrowings()->count();
        $this->assertEquals($reservationsBefore, $reservationsAfter);
        $this->assertEquals($borrowingsBefore, $borrowingsAfter);

        $author->delete();
        $publisher->delete();
        $category->delete();
        // $bookItem->delete();
        $book->delete();
        // $reservation->delete();
        // $user->delete();
        $admin->delete();
    }


    /** @test */
    public function borrowBookWrongUserError() {
        $admin = $this->logIn();
        $book = factory(Book::class)->create();
        $bookItem = factory(BookItem::class)->create();
        $author = factory(Author::class)->create();
        $publisher = factory(Publisher::class)->create();
        $category = factory(Category::class)->create();
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $publisher->books()->save($book);
        $author->books()->save($book);
        $category->books()->save($book);
        $book->bookItems()->save($bookItem);

        $reservation =  new Reservation(['due_date' =>  new DateTime("+3 days")]);
        $user->reservations($bookItem)->save($reservation);
        $reservationsBefore = $user->reservations()->count();
        $borrowingsBefore = $user->borrowings()->count();

        $data = array(
            'userId' => $user2->id,
            'bookItemId' => $bookItem->id,
            'reservationId' => $reservation->id
        );

        $response = $this->post('/pracownik/egzemplarze/' . $bookItem->id . '/rezerwacja/wypozycz', $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $reservationsAfter = $user->reservations()->count();
        $borrowingsAfter = $user->borrowings()->count();
        $this->assertEquals($reservationsBefore, $reservationsAfter);
        $this->assertEquals($borrowingsBefore, $borrowingsAfter);
        
        $author->delete();
        $publisher->delete();
        $category->delete();
        $bookItem->delete();
        $book->delete();
        $reservation->delete();
        $user->delete();
        $user2->delete();
        $admin->delete();
    }
}
