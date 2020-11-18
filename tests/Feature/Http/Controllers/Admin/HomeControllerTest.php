<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Admin;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeControllerTest extends TestCase {
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
    public function infoView() {
        $admin = $this->logIn();
        $response = $this->get('/pracownik/info');
        $response->assertViewIs('.admin.info');
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $admin->delete();
    }

    /** @test */
    public function mainSiteView() {
        $admin = $this->logIn();
        $response = $this->get('/pracownik');
        $response->assertViewIs('.admin.home');
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewHas(['borrowings', 'reservations', 'bookItems', 'newBorrowings', 'newReservations', 'newUsers', 'newBookItems']);
        $admin->delete();
    }

    /** @test */
    public function mainSiteUnauthenticated() {
        $response = $this->get('/pracownik');
        $response->assertRedirect('/pracownik/logowanie');
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }
}
