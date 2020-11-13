<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Tests\TestCase;
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
    public function userLoginDisplaysErrors() {
        $response = $this->post('/logowanie', []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function userLoginAuthenticates() {
    
        // if(User::where('pesel',100000000000)->get()->first()){
        //     User::where('pesel',100000000000)->get()->first()->delete();
        // }
        // if(User::where('pesel',99999999999)->get()->first()){
        //     User::where('pesel',99999999999)->get()->first()->delete();
        // }
        // if(User::where('pesel',100000000001)->get()->first()){
        //     User::where('pesel',100000000001)->get()->first()->delete();
        // }

        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->post('/logowanie', [
            'email' => $user->email,
            'password' => bcrypt('password')
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
        $user->delete();
    }
}
