<?php

namespace Tests\Feature\Http\Controllers\User;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Tests\TestCase;
use App\Http\Controllers\User\UserController;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase {

    public function logIn() {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $this->post('/logowanie', [
            'email' => $user->email,
            'password' => bcrypt('password')
        ]);
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
        $newName = 'new_first_name';
        $response = $this->post('/zmien-dane', [
            'first_name' => $newName,
        ]);
        $response->assertStatus(302);
        $this->assertEquals($user->first_name, $newName);
        $response->assertRedirect('/');
        $user->delete();
    }

    /** @test */
    public function updateProfileDuplicatedPESEL() {
        $user = $this->logIn();
        $anotherUser = factory(User::class)->create();
        $response = $this->post('/zmien-dane', [
            'first_name' => $anotherUser->first_name,
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $user->delete();
    }

    /** @test */
    public function deleteAccountWithBorrowedBooks() {
        $user = $this->logIn();
        $this->assertTrue(true);
        // $book = factory(Book::class)->create();
   
        // $borrowing = Borrowing::createWith([], ['user' => $user, 'bookItem'])
        // $response = $this->post('/usun-konto', []);
        // $response->assertStatus(302);
        // $response->assertSessionHasErrors();
        // $user->delete();
        // $book->delete();

        //TODO
    }
}
