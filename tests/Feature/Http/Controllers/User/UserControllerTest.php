<?php

namespace Tests\Feature\Http\Controllers\User;

use App\Models\Book;
use App\Models\BookItem;
use App\Models\Borrowing;
use App\Models\Reservation;
use App\Models\User;
use Tests\TestCase;
use DateTime;

use App\Http\Controllers\User\UserController;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase {

    use WithFaker;

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
    public function userInfoAfterSuccessfullyLogin() {
        $user = $this->logIn();

        $response = $this->get('/dane');
        $response->assertStatus(200);
        $response->assertViewIs('.user.userInfo');
        $response->assertSessionHasNoErrors();
        $response->assertViewHas('user');
        $content = $response->getOriginalContent()->getData();
        $this->assertEquals($content['user']['id'], $user->id);

        $user->delete();
    }

    /** @test */
    public function userInfoUnauthenticated() {
        $response = $this->get('/dane');
        $response->assertRedirect('/logowanie');
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function editProfileView() {
        $user = $this->logIn();

        $response = $this->get('/zmien-dane');
        $response->assertStatus(200);
        $response->assertViewIs('.user.editProfile');
        $response->assertSessionHasNoErrors();
        $response->assertViewHas('user');
        $content = $response->getOriginalContent()->getData();
        $this->assertEquals($content['user']['id'], $user->id);

        $user->delete();
    }

    /** @test */
    public function updateProfileSuccess() {
        $user = $this->logIn();
        $new = $this->faker->unique()->numberBetween(1, 999999);

        $data = array(
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'pesel' => $user->pesel,
            'phone' => $new,
        );
        $response = $this->post('/zmien-dane', $data);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $userUpdated = User::where('id', $user->id)->firstOrFail();
        $this->assertEquals($userUpdated->phone, $new);
        $response->assertRedirect('/dane');

        $user->delete();
    }

    /** @test */
    public function updateProfileDuplicatedPESEL() {
        $user = $this->logIn();
        $anotherUser = factory(User::class)->create();

        $data = array(
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'pesel' => $anotherUser->pesel,
            'phone' => $user->phone,
        );
        $response = $this->post('/zmien-dane', $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();

        $anotherUser->delete();
        $user->delete();
    }


    /** @test */
    public function deleteAccountWithoutBorrowedBooks() {
        $user = $this->logIn();
        $this->assertEquals($user->borrowings->count(), 0);
        $this->assertEquals($user->reservations->count(), 0);

        $response = $this->post('/usun-konto', []);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/');
        $userAfter = User::where('id', $user->id)->get();
        $this->assertEquals($userAfter->count(), 0);
    }


    /** @test */
    public function deleteAccountWithBorrowedBooks() {
        $user = $this->logIn();
        $bookItem = factory(BookItem::class)->create();
        $borrowing =  new Borrowing(['borrow_date' => new DateTime(), 'due_date' => new DateTime("+1 month"), 'was_prolonged' => false]);
        $user->borrowings($bookItem)->save($borrowing);
        $this->assertGreaterThan(0, $user->borrowings->count());

        $response = $this->post('/usun-konto', []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $userAfter = User::where('id', $user->id)->get();
        $this->assertNotEquals($userAfter->count(), 0);

        $user->delete();
        $borrowing->delete();
        $bookItem->delete();
    }

    /** @test */
    public function deleteAccountWithReservedBooks() {
        $user = $this->logIn();
        $bookItem = factory(BookItem::class)->create();
        $reservation =  new Reservation(['reservation_date' => new DateTime(), 'due_date' =>  strtotime("+3 days")]);
        $user->reservations($bookItem)->save($reservation);
        $this->assertGreaterThan(0, $user->reservations->count());

        $response = $this->post('/usun-konto', []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $userAfter = User::where('id', $user->id)->get();
        $this->assertNotEquals($userAfter->count(), 0);

        $user->delete();
        $reservation->delete();
        $bookItem->delete();
    }
}
