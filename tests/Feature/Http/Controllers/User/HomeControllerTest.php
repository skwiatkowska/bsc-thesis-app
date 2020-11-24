<?php

namespace Tests\Feature\Http\Controllers\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeControllerTest extends TestCase {
    /** @test */
    public function indexView() {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('.user.home');
        $response->assertSessionHasNoErrors();
    }


    /** @test */
    public function contactView() {
        $response = $this->get('/kontakt');
        $response->assertStatus(200);
        $response->assertViewIs('.user.contact');
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function workingHoursView() {
        $response = $this->get('/godziny-otwarcia');
        $response->assertStatus(200);
        $response->assertViewIs('.user.workingHours');
        $response->assertSessionHasNoErrors();
    }


    /** @test */
    public function firstStepsView() {
        $response = $this->get('/pierwsze-kroki');
        $response->assertStatus(200);
        $response->assertViewIs('.user.firstSteps');
        $response->assertSessionHasNoErrors();
    }
}
