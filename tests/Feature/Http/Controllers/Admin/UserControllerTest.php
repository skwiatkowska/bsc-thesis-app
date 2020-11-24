<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\BookItem;
use App\Models\Borrowing;
use App\Models\Reservation;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use DateTime;
use Illuminate\Support\Facades\Hash;


class UserControllerTest extends TestCase {
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
    public function createUserView() {
        $admin = $this->logIn();
        $response = $this->get('/pracownik/czytelnicy/nowy');
        $response->assertStatus(200);
        $response->assertViewIs('.admin.newUser');
        $admin->delete();
    }

    /** @test */
    public function createNewUserNoModal() {
        $admin = $this->logIn();
        $pesel = $this->faker->unique()->numberBetween(1, 99999999);
        $data = array(
            'email' => $this->faker->unique()->safeEmail,
            'fname' => $this->faker->firstName(),
            'lname' => $this->faker->lastName,
            'pesel' => $pesel,
            'phone' => $this->faker->unique()->numberBetween(1, 999999),
            'street' => $this->faker->name,
            'house_number' => $this->faker->numberBetween(1, 999999),
            'zipcode' => $this->faker->numberBetween(1, 999999),
            'city' => $this->faker->name,
            'password' => $this->faker->name,
            'isModal' => false
        );

        $response = $this->post('/pracownik/czytelnicy/nowy', $data);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $user = User::where('pesel', $pesel)->get()->first();
        $response->assertRedirect('/pracownik/czytelnicy/' . $user->id);

        $user->delete();
        $admin->delete();
    }


    /** @test */
    public function createNewUserFromModal() {
        $admin = $this->logIn();
        $pesel = $this->faker->unique()->numberBetween(1, 99999999);
        $data = array(
            'email' => $this->faker->unique()->safeEmail,
            'fname' => $this->faker->firstName(),
            'lname' => $this->faker->lastName,
            'pesel' => $pesel,
            'phone' => $this->faker->unique()->numberBetween(1, 999999),
            'street' => $this->faker->name,
            'house_number' => $this->faker->numberBetween(1, 999999),
            'zipcode' => $this->faker->numberBetween(1, 999999),
            'city' => $this->faker->name,
            'password' => $this->faker->name,
            'isModal' => true
        );

        $response = $this->post('/pracownik/czytelnicy/nowy', $data);
        $response->assertStatus(200);
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $content);

        $user = User::where('pesel', $pesel)->get()->first();
        $user->delete();
        $admin->delete();
    }


    /** @test */
    public function createNewUserDuplicatedPESEL() {
        $admin = $this->logIn();
        $user = factory(User::class)->create();
        $userBefore = User::all()->count();

        $data = array(
            'email' => $this->faker->unique()->safeEmail,
            'fname' => $this->faker->firstName(),
            'lname' => $this->faker->lastName,
            'pesel' => $user->pesel,
            'phone' => $this->faker->unique()->numberBetween(1, 999999),
            'street' => $this->faker->name,
            'house_number' => $this->faker->numberBetween(1, 999999),
            'zipcode' => $this->faker->numberBetween(1, 999999),
            'city' => $this->faker->name,
            'password' => $this->faker->name,
            'isModal' => false
        );

        $response = $this->post('/pracownik/czytelnicy/nowy', $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $userAfter = User::all()->count();
        $this->assertEquals($userAfter, $userBefore);

        $user->delete();
        $admin->delete();
    }


    /** @test */
    public function userInfoCorrectId() {
        $admin = $this->logIn();
        $user = factory(User::class)->create();
        $response = $this->get('/pracownik/czytelnicy/' . $user->id);
        $response->assertStatus(200);
        $response->assertViewIs('.admin.userInfo');
        $response->assertSessionHasNoErrors();
        $response->assertViewHas('user');
        $user->delete();
        $admin->delete();
    }


    /** @test */
    public function userInfoWrongId() {
        $admin = $this->logIn();
        $response = $this->get('/pracownik/czytelnicy/-1');
        $response->assertStatus(404);
        $response->assertNotFound();
        $admin->delete();
    }

    /** @test */
    public function updateProfileSuccess() {
        $admin = $this->logIn();
        $user = factory(User::class)->create();
        $new = $this->faker->unique()->numberBetween(1, 999999);

        $data = array(
            'name' => 'phone',
            'value' => $new,
        );
        $response = $this->post('/pracownik/czytelnicy/' . $user->id . '/edycja', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $content);
        $userUpdated = User::where('id', $user->id)->firstOrFail();
        $this->assertEquals($userUpdated->phone, $new);

        $user->delete();
        $admin->delete();
    }

    /** @test */
    public function updateProfileDuplicatedPESEL() {
        $admin = $this->logIn();
        $user = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();

        $data = array(
            'name' => 'pesel',
            'value' => $anotherUser->pesel,
        );
        $response =  $this->post('/pracownik/czytelnicy/' . $user->id . '/edycja', $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();

        $anotherUser->delete();
        $user->delete();
        $admin->delete();
    }


    /** @test */
    public function updateProfileDuplicatedEmail() {
        $admin = $this->logIn();
        $user = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();

        $data = array(
            'name' => 'email',
            'value' => $anotherUser->email,
        );
        $response =  $this->post('/pracownik/czytelnicy/' . $user->id . '/edycja', $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();

        $anotherUser->delete();
        $user->delete();
        $admin->delete();
    }


    /** @test */
    public function deleteAccountWithoutBorrowedBooks() {
        $admin = $this->logIn();
        $user = factory(User::class)->create();
        $this->assertEquals($user->borrowings->count(), 0);
        $this->assertEquals($user->reservations->count(), 0);

        $response = $this->post('/pracownik/czytelnicy/' . $user->id . '/usun', []);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/pracownik/czytelnicy/znajdz');
        $userAfter = User::where('id', $user->id)->get();
        $this->assertEquals($userAfter->count(), 0);

        $admin->delete();
    }


    /** @test */
    public function deleteAccountWithBorrowedBooks() {
        $admin = $this->logIn();
        $user = factory(User::class)->create();
        $bookItem = factory(BookItem::class)->create();
        $borrowing =  new Borrowing(['borrow_date' => new DateTime(), 'due_date' => new DateTime("+1 month"), 'was_prolonged' => false]);
        $user->borrowings($bookItem)->save($borrowing);
        $this->assertGreaterThan(0, $user->borrowings->count());

        $response = $this->post('/pracownik/czytelnicy/' . $user->id . '/usun', []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $userAfter = User::where('id', $user->id)->get();
        $this->assertNotEquals($userAfter->count(), 0);

        $admin->delete();
        $borrowing->delete();
        $bookItem->delete();
        $user->delete();
    }

    /** @test */
    public function deleteAccountWithReservedBooks() {
        $admin = $this->logIn();
        $user = factory(User::class)->create();
        $bookItem = factory(BookItem::class)->create();
        $reservation =  new Reservation(['reservation_date' => new DateTime(), 'due_date' =>  strtotime("+3 days")]);
        $user->reservations($bookItem)->save($reservation);
        $this->assertGreaterThan(0, $user->reservations->count());

        $response = $this->post('/pracownik/czytelnicy/' . $user->id . '/usun', []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $userAfter = User::where('id', $user->id)->get();
        $this->assertNotEquals($userAfter->count(), 0);

        $admin->delete();
        $reservation->delete();
        $bookItem->delete();
        $user->delete();
    }


    /** @test */
    public function resetPassword() {
        $admin = $this->logIn();
        $user = factory(User::class)->create();

        $response = $this->post('/pracownik/czytelnicy/' . $user->id . '/resetuj-haslo', []);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $content);

        $response = $this->get('/pracownik/wyloguj');
        $response->assertStatus(302);
        $response->assertRedirect('/pracownik/logowanie');
        $response->assertSessionHasNoErrors();

        $this->actingAs($user);
        $response = $this->post('/logowanie', [
            'email' => $user->email,
            'password' => $user->pesel
        ]);
        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/moje-ksiazki');

        $user->delete();
        $admin->delete();
    }


    /** @test */
    public function findUserInit() {
        $admin = $this->logIn();
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $response = $this->get('/pracownik/czytelnicy/znajdz', []);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.admin.findUser');
        $response->assertViewHas('users');
        $content = $response->getOriginalContent()->getData();
        $this->assertGreaterThanOrEqual(2, $content['users']->count());

        $user1->delete();
        $user2->delete();
        $admin->delete();
    }

    /** @test */
    public function findUserByPesel() {
        $admin = $this->logIn();
        $user = factory(User::class)->create();
        $data = array(
            'searchIn' => 'pesel',
            'phrase' => $user->pesel,
        );
        $response = $this->call('GET', '/pracownik/czytelnicy/znajdz', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.admin.findUser');
        $response->assertViewHas('users');
        $content = $response->getOriginalContent()->getData();
        $this->assertEquals($content['users']->count(), 1);

        $user->delete();
        $admin->delete();
    }


    /** @test */
    public function findUserWrongPesel() {
        $admin = $this->logIn();
        $user = factory(User::class)->create();

        $data = array(
            'searchIn' => 'pesel',
            'phrase' => -1,
        );
        $response = $this->call('GET', '/pracownik/czytelnicy/znajdz', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.admin.findUser');
        $response->assertViewHas('users');
        $content = $response->getOriginalContent()->getData();
        $this->assertEquals($content['users']->count(), 0);

        $user->delete();
        $admin->delete();
    }


    /** @test */
    public function findUserByLastName() {
        $admin = $this->logIn();
        $user = factory(User::class)->create();

        $data = array(
            'searchIn' => 'lname',
            'phrase' => $user->last_name,
        );
        $response = $this->call('GET', '/pracownik/czytelnicy/znajdz', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.admin.findUser');
        $response->assertViewHas('users');
        $content = $response->getOriginalContent()->getData();
        $this->assertGreaterThanOrEqual(1, $content['users']->count());

        $user->delete();
        $admin->delete();
    }


    /** @test */
    public function findUserWrongLastName() {
        $admin = $this->logIn();
        $user = factory(User::class)->create();

        $data = array(
            'searchIn' => 'lname',
            'phrase' => -1,
        );
        $response = $this->call('GET', '/pracownik/czytelnicy/znajdz', $data);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewIs('.admin.findUser');
        $response->assertViewHas('users');
        $content = $response->getOriginalContent()->getData();
        $this->assertEquals($content['users']->count(), 0);
        
        $user->delete();
        $admin->delete();
    }
}
