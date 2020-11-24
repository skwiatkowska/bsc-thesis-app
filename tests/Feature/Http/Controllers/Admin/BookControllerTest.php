<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookItem;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\Borrowing;
use App\Models\Reservation;
use App\Models\User;
use DateTime;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookControllerTest extends TestCase {
    use WithFaker;

    public function logIn() {
        $admin = factory(Admin::class)->create();
        $this->actingAs($admin);
        $response = $this->post('/pracownik/logowanie', [
            'email' => $admin->email,
            'password' => 'password'
        ]);
        $response->assertRedirect('/pracownik');
        $response->assertStatus(302);
        return $admin;
    }


    /** @test */
    public function createNewBookDisplaysFormView() {
        $admin = $this->logIn();
        $category = factory(Category::class)->create();
        $response = $this->get('/pracownik/ksiazki/nowa');
        $response->assertStatus(200);
        $response->assertViewIs('.admin.newBook');
        $response->assertSessionHasNoErrors();
        $response->assertViewHas(['categories', 'authors', 'publishers']);
        $category->delete();
        $admin->delete();
    }

    /** @test */
    public function createNewBook() {
        $admin = $this->logIn();
        $author = factory(Author::class)->create();
        $publisher = factory(Publisher::class)->create();
        $category = factory(Category::class)->create();
        $isbn = $this->faker->unique()->numberBetween(1, 999999999);
        $data = array(
            'title' => $this->faker->unique()->name,
            'isbn' => $isbn,
            'year' => $this->faker->numberBetween(1910, 2020),
            'numberOfItems' => $this->faker->numberBetween(1, 4),
            'authors' => array($author->id),
            'categories' => array($category->id),
            'publisher' => $publisher->id,
        );

        $response = $this->post('/pracownik/ksiazki/nowa', $data);
        $response->assertStatus(302);
        $book = Book::where('isbn', $isbn)->get()->first();
        $response->assertRedirect('/pracownik/ksiazki/' . $book->id);
        $author->delete();
        $publisher->delete();
        $category->delete();
        foreach ($book->bookItems as $bookItem) {
            $bookItem->delete();
        }
        $book->delete();
        $admin->delete();
    }

    /** @test */
    public function createNewBookDuplicatedError() {
        $admin = $this->logIn();
        $anotherBook = factory(Book::class)->create();
        $author = factory(Author::class)->create();
        $publisher = factory(Publisher::class)->create();
        $category = factory(Category::class)->create();
        $booksBefore = Book::all()->count();

        $data = array(
            'title' => $this->faker->unique()->name,
            'isbn' => $anotherBook->isbn,
            'year' => $this->faker->numberBetween(1910, 2020),
            'numberOfItems' => 0,
            'authors' => array($author->id),
            'categories' => array($category->id),
            'publisher' => $publisher->id,
        );
        $response = $this->post('/pracownik/ksiazki/nowa', $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $booksAfter = Book::all()->count();
        $this->assertEquals($booksBefore, $booksAfter);

        $author->delete();
        $publisher->delete();
        $category->delete();
        $anotherBook->delete();
        $admin->delete();
    }


    /** @test */
    public function bookInfoCorrectId() {
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
        $response = $this->get('/pracownik/ksiazki/' . $book->id);
        $response->assertStatus(200);
        $response->assertViewIs('.admin.bookInfo');
        $response->assertSessionHasNoErrors();
        $response->assertViewHas('book');
        $author->delete();
        $publisher->delete();
        $category->delete();
        $bookItem->delete();
        $book->delete();
        $admin->delete();
    }


    /** @test */
    public function bookInfoWrongId() {
        $admin = $this->logIn();
        $response = $this->get('/pracownik/ksiazki/-1');
        $response->assertStatus(404);
        $response->assertNotFound();
        $admin->delete();
    }



    /** @test */
    public function editBookView() {
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
        $response = $this->get('/pracownik/ksiazki/' . $book->id . '/edycja');
        $response->assertStatus(200);
        $response->assertViewIs('.admin.editBook');
        $response->assertSessionHasNoErrors();
        $response->assertViewHas('book');
        $content = $response->getOriginalContent()->getData();
        $this->assertEquals($content['book']['id'], $book->id);
        $author->delete();
        $publisher->delete();
        $category->delete();
        $bookItem->delete();
        $book->delete();
        $admin->delete();
    }


    /** @test */
    public function updateBookSuccess() {
        $admin = $this->logIn();
        $new = $this->faker->unique()->numberBetween(1, 999999);
        $book = factory(Book::class)->create();
        $author = factory(Author::class)->create();
        $publisher = factory(Publisher::class)->create();
        $category = factory(Category::class)->create();
        $publisher->books()->save($book);
        $author->books()->save($book);
        $category->books()->save($book);
        $new = $this->faker->unique()->numberBetween(1, 999999999);
        $data = array(
            'title' => $book->title,
            'isbn' => $new,
            'year' => $book->year,
            'authors' => array($book->authors->first()->id),
            'categories' => array($book->categories->first()->id),
            'publisher' => $book->publisher->id,
        );

        $response = $this->post('/pracownik/ksiazki/' . $book->id . '/edycja', $data);
        $response->assertStatus(302);
        $bookUpdated = Book::where('id', $book->id)->firstOrFail();
        $this->assertEquals($bookUpdated->isbn, $new);
        $response->assertRedirect('/pracownik/ksiazki/' . $book->id);
        $author->delete();
        $publisher->delete();
        $category->delete();
        $book->delete();
        $admin->delete();
    }


    /** @test */
    public function updateBookDuplicatedISBN() {
        $admin = $this->logIn();
        $book = factory(Book::class)->create();
        $book2 = factory(Book::class)->create();
        $author = factory(Author::class)->create();
        $publisher = factory(Publisher::class)->create();
        $category = factory(Category::class)->create();

        $publisher->books()->save($book);
        $author->books()->save($book);
        $category->books()->save($book);

        $publisher->books()->save($book2);
        $author->books()->save($book2);
        $category->books()->save($book2);
        $data = array(
            'title' => $book->title,
            'isbn' => $book2->isbn,
            'year' => $book->year,
            'authors' => array($book->authors->first()->id),
            'categories' => array($book->categories->first()->id),
            'publisher' => $book->publisher->id,
        );

        $response = $this->post('/pracownik/ksiazki/' . $book->id . '/edycja', $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $response->assertRedirect('/pracownik/ksiazki/' . $book->id . '/edycja');
        $author->delete();
        $publisher->delete();
        $category->delete();
        $book->delete();
        $book2->delete();
        $admin->delete();
    }



    /** @test */
    public function findBookInit() {
        $admin = $this->logIn();
        $book = factory(Book::class)->create();
        $bookItem = factory(BookItem::class)->create();
        $author = factory(Author::class)->create();
        $publisher = factory(Publisher::class)->create();
        $publisher->books()->save($book);
        $author->books()->save($book);
        $book->bookItems()->save($bookItem);

        $response = $this->get('/pracownik/katalog');
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.admin.catalog');
        $response->assertViewHas('books');
        $content = $response->getOriginalContent()->getData();
        $this->assertGreaterThanOrEqual(1, $content['books']->count());

        $author->delete();
        $publisher->delete();
        $bookItem->delete();
        $book->delete();
        $admin->delete();
    }

    /** @test */
    public function findBookByCategory() {
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
        $data = array(
            'searchIn' => 'category',
            'searchPhrase' => $category->id,
        );
        $response = $this->call('GET', '/pracownik/katalog', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.admin.catalog');
        $response->assertViewHas('books');
        $content = $response->getOriginalContent()->getData();
        $this->assertGreaterThanOrEqual(1, $content['books']->count());

        //0 results
        $category2 = factory(Category::class)->create();

        $data = array(
            'searchIn' => 'category',
            'searchPhrase' => $category2->id,
        );
        $response = $this->call('GET', '/pracownik/katalog', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.admin.catalog');
        $response->assertViewHas('books');
        $content = $response->getOriginalContent()->getData();
        $this->assertEquals($content['books']->count(), 0);

        $author->delete();
        $publisher->delete();
        $category->delete();
        $category2->delete();
        $bookItem->delete();
        $book->delete();
        $admin->delete();
    }


    /** @test */
    public function findBookByAuthor() {
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
        $data = array(
            'searchIn' => 'author',
            'phrase' => $author->last_name,
        );
        $response = $this->call('GET', '/pracownik/katalog', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.admin.catalog');
        $response->assertViewHas('books');
        $content = $response->getOriginalContent()->getData();
        $this->assertGreaterThanOrEqual(1, $content['books']->count());

        //0 results
        $author2 = factory(Author::class)->create();

        $data = array(
            'searchIn' => 'author',
            'phrase' => $author2->last_name,
        );
        $response = $this->call('GET', '/pracownik/katalog', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.admin.catalog');
        $response->assertViewHas('books');
        $content = $response->getOriginalContent()->getData();
        $this->assertEquals($content['books']->count(), 0);
        $author->delete();
        $author2->delete();
        $publisher->delete();
        $category->delete();
        $bookItem->delete();
        $book->delete();
        $admin->delete();
    }


    /** @test */
    public function findBookByPublisher() {
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
        $data = array(
            'searchIn' => 'publisher',
            'phrase' => $publisher->name,
        );
        $response = $this->call('GET', '/pracownik/katalog', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.admin.catalog');
        $response->assertViewHas('books');
        $content = $response->getOriginalContent()->getData();
        $this->assertGreaterThanOrEqual(1, $content['books']->count());

        //0 results
        $publisher2 = factory(Publisher::class)->create();
        $data = array(
            'searchIn' => 'publisher',
            'phrase' => $publisher2->name,
        );
        $response = $this->call('GET', '/pracownik/katalog', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.admin.catalog');
        $response->assertViewHas('books');
        $content = $response->getOriginalContent()->getData();
        $this->assertEquals($content['books']->count(), 0);
        $author->delete();
        $publisher->delete();
        $publisher2->delete();
        $category->delete();
        $bookItem->delete();
        $book->delete();
        $admin->delete();
    }


    /** @test */
    public function findBookByTitle() {
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
        $data = array(
            'searchIn' => 'title',
            'phrase' => $book->title,
        );
        $response = $this->call('GET', '/pracownik/katalog', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.admin.catalog');
        $response->assertViewHas('books');
        $content = $response->getOriginalContent()->getData();
        $this->assertGreaterThanOrEqual(1, $content['books']->count());

        //0 results
        $data = array(
            'searchIn' => 'title',
            'phrase' => '-11111111111111111',
        );
        $response = $this->call('GET', '/pracownik/katalog', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.admin.catalog');
        $response->assertViewHas('books');
        $content = $response->getOriginalContent()->getData();
        $this->assertEquals($content['books']->count(), 0);
        $author->delete();
        $publisher->delete();
        $category->delete();
        $bookItem->delete();
        $book->delete();
        $admin->delete();
    }


    /** @test */
    public function findBookByISBN() {
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
        $data = array(
            'searchIn' => 'isbn',
            'phrase' => $book->isbn,
        );
        $response = $this->call('GET', '/pracownik/katalog', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.admin.catalog');
        $response->assertViewHas('books');
        $content = $response->getOriginalContent()->getData();
        $this->assertEquals($content['books']->count(), 1);


        $data = array(
            'searchIn' => 'isbn',
            'phrase' => '-1111111',
        );
        $response = $this->call('GET', '/pracownik/katalog', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.admin.catalog');
        $response->assertViewHas('books');
        $content = $response->getOriginalContent()->getData();
        $this->assertEquals($content['books']->count(), 0);
        $author->delete();
        $publisher->delete();
        $category->delete();
        $bookItem->delete();
        $book->delete();
        $admin->delete();
    }



    /** @test */
    public function deleteBookWithoutBookItems() {
        $admin = $this->logIn();
        $book = factory(Book::class)->create();
        $author = factory(Author::class)->create();
        $publisher = factory(Publisher::class)->create();
        $category = factory(Category::class)->create();
        $publisher->books()->save($book);
        $author->books()->save($book);
        $category->books()->save($book);

        $bookBefore = Book::all()->count();

        $response = $this->post('/pracownik/ksiazki/' . $book->id . '/usun', ['id' => $book->id]);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/pracownik/katalog');
        $bookAfter = Book::all()->count();
        $this->assertLessThan($bookBefore, $bookAfter);
        $author->delete();
        $publisher->delete();
        $category->delete();
        $admin->delete();
    }


    /** @test */
    public function deleteBookWithBookItems() {
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

        $bookBefore = Book::all()->count();
        $response = $this->post('/pracownik/ksiazki/' . $book->id . '/usun', ['id' => $book->id]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $response->assertRedirect('/pracownik/ksiazki/' . $book->id);

        $bookAfter = Book::all()->count();
        $this->assertEquals($bookBefore, $bookAfter);
        $author->delete();
        $publisher->delete();
        $category->delete();
        $bookItem->delete();
        $book->delete();
        $admin->delete();
    }


    //BOOK ITEMS

    /** @test */
    public function bookItemInfoCorrectId() {
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
        $response = $this->get('/pracownik/egzemplarze/' . $bookItem->id);
        $response->assertStatus(200);
        $response->assertViewIs('.admin.bookItemInfo');
        $response->assertSessionHasNoErrors();
        $response->assertViewHas('item');
        $author->delete();
        $publisher->delete();
        $category->delete();
        $bookItem->delete();
        $book->delete();
        $admin->delete();
    }


    /** @test */
    public function bookItemInfoWrongId() {
        $admin = $this->logIn();
        $response = $this->get('/pracownik/egzemplarze/-1');
        $response->assertStatus(404);
        $response->assertNotFound();
        $admin->delete();
    }


    /** @test */
    public function blockUnlockBookItemSuccess() {
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

        $bookItemIsBlockedBefore = $bookItem->is_blocked;
        $data = array(
            'bookId' => $book->id,
            'order' => $bookItem->book_item_id
        );
        $response = $this->post('/pracownik/egzemplarze/' . $bookItem->id . '/blokuj', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $bookItem = BookItem::where('id', $bookItem->id)->get()->first();
        $bookItemIsBlockedAfter = $bookItem->is_blocked;
        $this->assertNotEquals($bookItemIsBlockedAfter, $bookItemIsBlockedBefore);

        $response = $this->post('/pracownik/egzemplarze/' . $bookItem->id . '/blokuj', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $content);
        $bookItem = BookItem::where('id', $bookItem->id)->get()->first();
        $bookItemIsBlockedAfter2 = $bookItem->is_blocked;
        $this->assertEquals($bookItemIsBlockedAfter2, $bookItemIsBlockedBefore);

        $author->delete();
        $publisher->delete();
        $category->delete();
        $bookItem->delete();
        $book->delete();
        $admin->delete();
    }


    /** @test */
    public function blockUnlockBookItemErrorBookItemNotAvailable() {
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
        $bookItem->update(['status' => BookItem::RESERVED]);

        $bookItemIsBlockedBefore = $bookItem->is_blocked;
        $data = array(
            'bookId' => $book->id,
            'order' => $bookItem->book_item_id
        );
        $response = $this->post('/pracownik/egzemplarze/' . $bookItem->id . '/blokuj', $data);
        $response->assertStatus(403);
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $content);
        $bookItem = BookItem::where('id', $bookItem->id)->get()->first();
        $bookItemIsBlockedAfter = $bookItem->is_blocked;
        $this->assertEquals($bookItemIsBlockedAfter, $bookItemIsBlockedBefore);

        $author->delete();
        $publisher->delete();
        $category->delete();
        $bookItem->delete();
        $book->delete();
        $admin->delete();
    }



    /** @test */
    public function createBookItemSuccess() {
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

        $bookItemsBefore = BookItem::all()->count();
        $data = array(
            'bookId' => $book->id,
            'order' => $bookItem->book_item_id + 1
        );
        $response = $this->post('/pracownik/ksiazki/' . $book->id . '/nowy-egzemplarz', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $content);

        $bookItemsAfter = BookItem::all()->count();
        $this->assertGreaterThan($bookItemsBefore, $bookItemsAfter);

        $author->delete();
        $publisher->delete();
        $category->delete();
        foreach ($book->bookItems as $bookItem) {
            $bookItem->delete();
        }
        $book->delete();
        $admin->delete();
    }


    /** @test */
    public function createBookItemDuplicatedError() {
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

        $bookItemsBefore = BookItem::all()->count();
        $data = array(
            'bookId' => $book->id,
            'order' => $bookItem->book_item_id
        );
        $response = $this->post('/pracownik/ksiazki/' . $book->id . '/nowy-egzemplarz', $data);
        $response->assertStatus(409);
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $content);

        $bookItemsAfter = BookItem::all()->count();
        $this->assertEquals($bookItemsBefore, $bookItemsAfter);

        $author->delete();
        $publisher->delete();
        $category->delete();
        $bookItem->delete();
        $book->delete();
        $admin->delete();
    }


    /** @test */
    public function deleteBookItemAvailableStatus() {
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

        $response = $this->post('/pracownik/egzemplarze/' . $bookItem->id . '/usun', ['id' => $bookItem->id]);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $content);

        $author->delete();
        $publisher->delete();
        $category->delete();
        $bookItem->delete();
        $book->delete();
        $admin->delete();
    }


    /** @test */
    public function deleteBookItemNotAvailableStatus() {
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

        $bookItem->update(['status' => BookItem::RESERVED]);

        $response = $this->post('/pracownik/egzemplarze/' . $bookItem->id . '/usun', ['id' => $bookItem->id]);
        $response->assertStatus(403);
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $content);

        $author->delete();
        $publisher->delete();
        $category->delete();
        $bookItem->delete();
        $book->delete();
        $admin->delete();
    }
}
