<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Tests\TestCase;
use App\Models\User;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterControllerTest extends TestCase {

    use WithFaker;
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
            'password' => $this->faker->name
        );
        $response = $this->post('/rejestracja', $data);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/dane');
        $user = User::where('pesel', $pesel)->get()->first();
        $this->assertAuthenticatedAs($user);

        $user->delete();
    }

    /** @test */
    public function userRegisterDisplaysValidationErrorsDuplicatedPESEL() {
        $user = factory(User::class)->create();

        $data = array(
            'email' => $this->faker->unique()->safeEmail,
            'fname' => $this->faker->firstName,
            'lname' => $this->faker->lastName,
            'pesel' => $user->pesel,
            'phone' => $this->faker->unique()->numberBetween(1, 999999),
            'street' => $this->faker->name,
            'house_number' => $this->faker->numberBetween(1, 999999),
            'zipcode' => $this->faker->numberBetween(1, 999999),
            'city' => $this->faker->name,
            'password' => $this->faker->name
        );
        $response = $this->post('/rejestracja', $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $response->assertRedirect('/rejestracja');

        $user->delete();
    }


    /** @test */
    public function userRegisterDisplaysValidationErrorsDuplicatedEmail() {
        $user = factory(User::class)->create();

        $data = array(
            'email' => $user->email,
            'fname' => $this->faker->firstName,
            'lname' => $this->faker->lastName,
            'pesel' => $this->faker->unique()->numberBetween(1, 999999999),
            'phone' => $this->faker->unique()->numberBetween(1, 999999),
            'street' => $this->faker->name,
            'house_number' => $this->faker->numberBetween(1, 999999),
            'zipcode' => $this->faker->numberBetween(1, 999999),
            'city' => $this->faker->name,
            'password' => $this->faker->name
        );
        $response = $this->post('/rejestracja', $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $response->assertRedirect('/rejestracja');
        
        $user->delete();
    }
}
