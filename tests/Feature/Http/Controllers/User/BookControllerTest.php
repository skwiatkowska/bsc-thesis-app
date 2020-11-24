<?php

namespace Tests\Feature\Http\Controllers\User;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookItem;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\Borrowing;
use App\Models\User;
use DateTime;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookControllerTest extends TestCase {

    use WithFaker;

    public function logIn() {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $response = $this->post('/logowanie', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $this->assertAuthenticatedAs($user);
        return $user;
    }

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
        $response = $this->get('/ksiazki/' . $book->id);
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
        $response = $this->get('/autorzy/' . $author->id);
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
        $response = $this->get('/wydawnictwa/' . $publisher->id);
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



    /** @test */
    public function findBookInit() {
        $book = factory(Book::class)->create();
        $bookItem = factory(BookItem::class)->create();
        $author = factory(Author::class)->create();
        $publisher = factory(Publisher::class)->create();
        $publisher->books()->save($book);
        $author->books()->save($book);
        $book->bookItems()->save($bookItem);
        $response = $this->get('/katalog');
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.user.catalog');
        $response->assertViewHas('books');
        $content = $response->getOriginalContent()->getData();
        $this->assertGreaterThanOrEqual(1, $content['books']->count());
        $author->delete();
        $publisher->delete();
        $bookItem->delete();
        $book->delete();
    }

    /** @test */
    public function findBookByCategory() {
        $book = factory(Book::class)->create();
        $bookItem = factory(BookItem::class)->create();
        $author = factory(Author::class)->create();
        $publisher = factory(Publisher::class)->create();
        $category = factory(Category::class)->create();
        $publisher->books()->save($book);
        $author->books()->save($book);
        $category->books()->save($book);
        $book->bookItems()->save($bookItem);
        $data = array(
            'searchIn' => 'category',
            'searchPhrase' => $category->id,
        );
        $response = $this->call('GET', '/katalog', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.user.catalog');
        $response->assertViewHas('books');
        $content = $response->getOriginalContent()->getData();
        $this->assertGreaterThanOrEqual(1, $content['books']->count());

        //0 results
        $category2 = factory(Category::class)->create();

        $data = array(
            'searchIn' => 'category',
            'searchPhrase' => $category2->id,
        );
        $response = $this->call('GET', '/katalog', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.user.catalog');
        $response->assertViewHas('books');
        $content = $response->getOriginalContent()->getData();
        $this->assertEquals($content['books']->count(), 0);

        $author->delete();
        $publisher->delete();
        $category->delete();
        $category2->delete();
        $bookItem->delete();
        $book->delete();
    }


    /** @test */
    public function findBookByAuthor() {
        $book = factory(Book::class)->create();
        $bookItem = factory(BookItem::class)->create();
        $author = factory(Author::class)->create();
        $publisher = factory(Publisher::class)->create();
        $category = factory(Category::class)->create();
        $publisher->books()->save($book);
        $author->books()->save($book);
        $category->books()->save($book);
        $book->bookItems()->save($bookItem);
        $data = array(
            'searchIn' => 'author',
            'phrase' => $author->last_name,
        );
        $response = $this->call('GET', '/katalog', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.user.catalog');
        $response->assertViewHas('books');
        $content = $response->getOriginalContent()->getData();
        $this->assertGreaterThanOrEqual(1, $content['books']->count());

        //0 results
        $author2 = factory(Author::class)->create();

        $data = array(
            'searchIn' => 'author',
            'phrase' => $author2->last_name,
        );
        $response = $this->call('GET', '/katalog', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.user.catalog');
        $response->assertViewHas('books');
        $content = $response->getOriginalContent()->getData();
        $this->assertEquals($content['books']->count(), 0);
        $author->delete();
        $author2->delete();
        $publisher->delete();
        $category->delete();
        $bookItem->delete();
        $book->delete();
    }


    /** @test */
    public function findBookByPublisher() {
        $book = factory(Book::class)->create();
        $bookItem = factory(BookItem::class)->create();
        $author = factory(Author::class)->create();
        $publisher = factory(Publisher::class)->create();
        $category = factory(Category::class)->create();
        $publisher->books()->save($book);
        $author->books()->save($book);
        $category->books()->save($book);
        $book->bookItems()->save($bookItem);
        $data = array(
            'searchIn' => 'publisher',
            'phrase' => $publisher->name,
        );
        $response = $this->call('GET', '/katalog', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.user.catalog');
        $response->assertViewHas('books');
        $content = $response->getOriginalContent()->getData();
        $this->assertGreaterThanOrEqual(1, $content['books']->count());

        //0 results
        $publisher2 = factory(Publisher::class)->create();
        $data = array(
            'searchIn' => 'publisher',
            'phrase' => $publisher2->name,
        );
        $response = $this->call('GET', '/katalog', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.user.catalog');
        $response->assertViewHas('books');
        $content = $response->getOriginalContent()->getData();
        $this->assertEquals($content['books']->count(), 0);
        $author->delete();
        $publisher->delete();
        $publisher2->delete();
        $category->delete();
        $bookItem->delete();
        $book->delete();
    }


    /** @test */
    public function findBookByTitle() {
        $book = factory(Book::class)->create();
        $bookItem = factory(BookItem::class)->create();
        $author = factory(Author::class)->create();
        $publisher = factory(Publisher::class)->create();
        $category = factory(Category::class)->create();
        $publisher->books()->save($book);
        $author->books()->save($book);
        $category->books()->save($book);
        $book->bookItems()->save($bookItem);
        $data = array(
            'searchIn' => 'title',
            'phrase' => $book->title,
        );
        $response = $this->call('GET', '/katalog', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.user.catalog');
        $response->assertViewHas('books');
        $content = $response->getOriginalContent()->getData();
        $this->assertGreaterThanOrEqual(1, $content['books']->count());

        //0 results
        $data = array(
            'searchIn' => 'title',
            'phrase' => '-11111111111111111',
        );
        $response = $this->call('GET', '/katalog', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.user.catalog');
        $response->assertViewHas('books');
        $content = $response->getOriginalContent()->getData();
        $this->assertEquals($content['books']->count(), 0);
        $author->delete();
        $publisher->delete();
        $category->delete();
        $bookItem->delete();
        $book->delete();
    }


    /** @test */
    public function findBookByISBN() {
        $book = factory(Book::class)->create();
        $bookItem = factory(BookItem::class)->create();
        $author = factory(Author::class)->create();
        $publisher = factory(Publisher::class)->create();
        $category = factory(Category::class)->create();
        $publisher->books()->save($book);
        $author->books()->save($book);
        $category->books()->save($book);
        $book->bookItems()->save($bookItem);
        $data = array(
            'searchIn' => 'isbn',
            'phrase' => $book->isbn,
        );
        $response = $this->call('GET', '/katalog', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.user.catalog');
        $response->assertViewHas('books');
        $content = $response->getOriginalContent()->getData();
        $this->assertEquals($content['books']->count(), 1);


        $data = array(
            'searchIn' => 'isbn',
            'phrase' => '-1111111',
        );
        $response = $this->call('GET', '/katalog', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.user.catalog');
        $response->assertViewHas('books');
        $content = $response->getOriginalContent()->getData();
        $this->assertEquals($content['books']->count(), 0);
        $author->delete();
        $publisher->delete();
        $category->delete();
        $bookItem->delete();
        $book->delete();
    }


    /** @test */
    public function prolongBookItemSuccess() {
        $user = $this->logIn();
        $bookItem = factory(BookItem::class)->create();
        $borrowing =  new Borrowing(['borrow_date' => new DateTime(), 'due_date' => new DateTime("+1 month"), 'was_prolonged' => false]);
        $user->borrowings($bookItem)->save($borrowing);
        $this->assertEquals($user->borrowings->count(), 1);
        $data = array(
            'id' => $bookItem->id,
        );
        $response = $this->post('/prolonguj', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $content);
        $borrowing->delete();
        $user->delete();
        $bookItem->delete();
    }

    public function prolongBookItemError() {
        $user = $this->logIn();
        $bookItem = factory(BookItem::class)->create();
        $this->assertEquals($user->borrowings->count(), 1);
        $data = array(
            'id' => $bookItem->id,
        );
        $response = $this->post('/prolonguj', $data);
        $response->assertSessionHasErrors();
        $response->assertStatus(404);
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $content);
        $user->delete();
        $bookItem->delete();
    }
}
