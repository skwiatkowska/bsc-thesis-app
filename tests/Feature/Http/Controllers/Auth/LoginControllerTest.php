<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\User;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginControllerTest extends TestCase {
    /** @test */
    public function userLoginDisplaysLoginForm() {
        $response = $this->get('/logowanie');
        $response->assertStatus(200);
        $response->assertViewIs('.user.login');
    }

    /** @test */
    public function userLoginDisplaysValidationErrors() {
        $response = $this->post('/logowanie', []);
        $response->assertStatus(302);
    }

    /** @test */
    public function userLoginAuthenticatesAndRedirects() {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $response = $this->post('/logowanie', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/moje-ksiazki');
        $this->assertAuthenticatedAs($user);
        $user->delete();
    }


    /** @test */
    public function adminLoginDisplaysLoginForm() {
        $response = $this->get('/pracownik/logowanie');
        $response->assertStatus(200);
        $response->assertViewIs('.admin.login');
    }

    /** @test */
    public function adminLoginDisplaysValidationErrors() {
        $response = $this->post('/pracownik/logowanie', []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function adminLoginAuthenticatesAndRedirects() {
        $admin = factory(Admin::class)->create();
        $this->actingAs($admin);
        $response = $this->post('/pracownik/logowanie', [
            'email' => $admin->email,
            'password' => 'password'
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertAuthenticatedAs($admin);
        $admin->delete();
    }

    /** @test */
    public function adminLoginAttemptedByUser() {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $response = $this->post('/pracownik/logowanie', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertSessionHasErrors();
        $user->delete();

    }

    /** @test */
    public function userLoginAttemptedByAdmin() {
        $admin = factory(Admin::class)->create();
        $this->actingAs($admin);
        $response = $this->post('/logowanie', [
            'email' => $admin->email,
            'password' => 'password'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertSessionHasErrors();
        $admin->delete();

    }
}
