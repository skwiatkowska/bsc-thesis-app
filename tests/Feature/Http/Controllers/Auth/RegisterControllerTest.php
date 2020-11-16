<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Tests\TestCase;
use App\Models\User;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterControllerTest extends TestCase {
    /** @test */
    public function userRegisterDisplaysRegisterForm() {
        $response = $this->get('/rejestracja');
        $response->assertStatus(200);
        $response->assertViewIs('.user.register');
    }

    /** @test */
    public function userRegisterDisplaysValidationErrors() {
        $response = $this->post('/rejestracja', []);
        $response->assertStatus(302);
    }

    /** @test */
    public function userRegisterSuccess() {
        $pesel = 1234567890;
        $data = array(
            'email' => 'testemail123455@test.test',
            'fname' => 'test',
            'lname' => 'test',
            'pesel' => $pesel,
            'phone' => '123',
            'street' => 'street',
            'house_number' => 1,
            'zipcode' => 1,
            'city' => 'bigcity',
            'password' => bcrypt('strongpassword')
        );

        $response = $this->post('/rejestracja', $data);
        $response->assertStatus(302);
        $response->assertRedirect('/dane');
        $user = User::where('pesel', $pesel)->get()->first();
        $this->assertAuthenticatedAs($user);
        $user->delete();
    }

    /** @test */
    public function userRegisterDisplaysValidationErrorsDuplicatedPESEL() {
        $user = factory(User::class)->create();

        $data = array(
            'email' => 'testemail123455@test.test',
            'fname' => 'test',
            'lname' => 'test',
            'pesel' => $user->pesel,
            'phone' => '123',
            'street' => 'street',
            'house_number' => 1,
            'zipcode' => 1,
            'city' => 'bigcity',
            'password' => bcrypt('strongpassword')
        );

        $response = $this->post('/rejestracja', $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $user->delete();
        $response->assertRedirect('/rejestracja');
    }


    /** @test */
    public function userRegisterDisplaysValidationErrorsDuplicatedEmail() {
        $user = factory(User::class)->create();

        $data = array(
            'email' => $user->email,
            'fname' => 'test',
            'lname' => 'test',
            'pesel' => 123456,
            'phone' => '123',
            'street' => 'street',
            'house_number' => 1,
            'zipcode' => 1,
            'city' => 'bigcity',
            'password' => bcrypt('strongpassword')
        );

        $response = $this->post('/rejestracja', $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $user->delete();
        $response->assertRedirect('/rejestracja');
    }
}
