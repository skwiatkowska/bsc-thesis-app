<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookItem;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\User;
use DateTime;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BorrowingControllerTest extends TestCase {
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
    public function borrowingList() {
        $admin = $this->logIn();
        $response = $this->get('/pracownik/wypozyczenia');
        $response->assertViewIs('.admin.borrowings');
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewHas('borrowings');
        $admin->delete();
    }


    /** @test */
    public function createBorrowingUserListView() {
        $admin = $this->logIn();
        $book = factory(Book::class)->create();
        $bookItem = factory(BookItem::class)->create();
        $author = factory(Author::class)->create();
        $publisher = factory(Publisher::class)->create();
        $category = factory(Category::class)->create();
        $publisher->books()->save($book);
        $author->books()->save($book);
        $category->books()->save($book);
        $book->bookItems()->save($bookItem);

        $response = $this->get('/pracownik/egzemplarze/' . $bookItem->id . '/wypozycz');
        $response->assertViewIs('.admin.addUserToBorrowing');
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewHas(['item', 'book', 'users']);

        $author->delete();
        $publisher->delete();
        $category->delete();
        $bookItem->delete();
        $book->delete();
        $admin->delete();
    }


    /** @test */
    public function createBorrowingFindUser() {
        $admin = $this->logIn();
        $book = factory(Book::class)->create();
        $bookItem = factory(BookItem::class)->create();
        $author = factory(Author::class)->create();
        $publisher = factory(Publisher::class)->create();
        $category = factory(Category::class)->create();
        $publisher->books()->save($book);
        $author->books()->save($book);
        $category->books()->save($book);
        $book->bookItems()->save($bookItem);
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $data = array(
            'searchIn' => 'pesel',
            'phrase' => $user->pesel,
        );
        $response = $this->post('/pracownik/egzemplarze/' . $bookItem->id . '/wypozycz', $data);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $response->assertViewIs('.admin.addUserToBorrowing');
        $response->assertViewHas(['item', 'book', 'users']);
        $content = $response->getOriginalContent()->getData();
        $this->assertEquals($content['users']->count(), 1);

        $user->delete();
        $user2->delete();
        $author->delete();
        $publisher->delete();
        $category->delete();
        $bookItem->delete();
        $book->delete();
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

        $publisher->books()->save($book);
        $author->books()->save($book);
        $category->books()->save($book);
        $book->bookItems()->save($bookItem);
        $user = factory(User::class)->create();

        $data = array(
            'userId' => $user->id,
            'bookItemId' => $bookItem->id,
        );
        $response = $this->post('/pracownik/egzemplarze/' . $bookItem->id . '/wypozycz/zapisz', $data);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/pracownik/czytelnicy/' . $user->id);

        $borrowingsAfter = $user->borrowings()->count();
        $this->assertGreaterThan(0, $borrowingsAfter);
        $borrowing = $user->borrowings()->first();

        $user->delete();
        $author->delete();
        $publisher->delete();
        $category->delete();
        $bookItem->delete();
        $borrowing->delete();
        $book->delete();
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
        $publisher->books()->save($book);
        $author->books()->save($book);
        $category->books()->save($book);
        $book->bookItems()->save($bookItem);
        $user = factory(User::class)->create();

        $bookItem->update(['status' => BookItem::BORROWED]);

        $data = array(
            'userId' => $user->id,
            'bookItemId' => $bookItem->id,
        );
        $response = $this->post('/pracownik/egzemplarze/' . $bookItem->id . '/wypozycz/zapisz', $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $borrowingsAfter = $user->borrowings()->count();
        $this->assertEquals(0, $borrowingsAfter);

        $user->delete();
        $author->delete();
        $publisher->delete();
        $category->delete();
        $bookItem->delete();
        $book->delete();
        $admin->delete();
    }


    /** @test */
    public function prolongBookItemSuccess() {
        $admin = $this->logIn();
        $user = factory(User::class)->create();
        $bookItem = factory(BookItem::class)->create();
        $borrowing =  new Borrowing(['borrow_date' => new DateTime(), 'due_date' => new DateTime("+1 month"), 'was_prolonged' => false]);
        $user->borrowings($bookItem)->save($borrowing);
        $dueDateBefore = $borrowing->due_date;
        $this->assertEquals($user->borrowings->count(), 1);

        $data = array(
            'id' => $bookItem->id,
        );
        $response = $this->put('/pracownik/egzemplarze/' . $bookItem->id . '/prolonguj', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $content);
        $borrowingAfter = Borrowing::where('id', $borrowing->id)->firstOrFail();
        $dueDateAfter = $borrowingAfter->due_date;
        
        $borrowing->delete();
        $user->delete();
        $bookItem->delete();
        $admin->delete();
    }

    public function prolongBookItemError() {
        $admin = $this->logIn();
        $user = factory(User::class)->create();
        $bookItem = factory(BookItem::class)->create();
        $this->assertEquals($user->borrowings->count(), 1);
        $data = array(
            'id' => $bookItem->id,
        );
        $response = $this->put('/pracownik/egzemplarze/' . $bookItem->id . '/prolonguj', $data);
        $response->assertSessionHasErrors();
        $response->assertStatus(404);
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $content);

        $user->delete();
        $bookItem->delete();
        $admin->delete();
    }


    /** @test */
    public function returnBookItemSuccess() {
        $admin = $this->logIn();
        $user = factory(User::class)->create();
        $bookItem = factory(BookItem::class)->create();
        $borrowing =  new Borrowing(['borrow_date' => new DateTime(), 'due_date' => new DateTime("+1 month"), 'was_prolonged' => false]);
        $user->borrowings($bookItem)->save($borrowing);
        $borrowingsBefore = $user->borrowings()->count();

        $data = array(
            'id' => $bookItem->id,
        );
        $response = $this->put('/pracownik/egzemplarze/' . $bookItem->id . '/zwroc', $data);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $borrowingsAfter = $user->borrowings()->count();
        $this->assertEquals($borrowingsBefore, $borrowingsAfter);
        $bookItemStatusAfter = $bookItem->status;
        $this->assertEquals($bookItemStatusAfter, BookItem::AVAILABLE);

        $user->delete();
        $bookItem->delete();
        $borrowing->delete();
        $admin->delete();
    }
}
