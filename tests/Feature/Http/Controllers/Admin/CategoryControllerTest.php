<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Book;
use App\Models\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryControllerTest extends TestCase {
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
    public function categoriesList() {
        $admin = $this->logIn();
        $response = $this->get('/pracownik/kategorie');
        $response->assertViewIs('.admin.categories');
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertViewHas('categories');
        $admin->delete();
    }

    /** @test */
    public function createNewCategorySuccess() {
        $admin = $this->logIn();
        $categoriesBefore = Category::all()->count();
        $name = $this->faker->unique()->name;

        $response = $this->post('/pracownik/kategorie', ['name' => $name]);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $content);
        $categoriesAfter = Category::all()->count();
        $this->assertGreaterThan($categoriesBefore, $categoriesAfter);
        $category = Category::where('name', $name)->get()->first();
        $category->delete();
        $admin->delete();
    }


    /** @test */
    public function createNewCategoryDuplicatedError() {
        $admin = $this->logIn();
        $anotherCategory = factory(Category::class)->create();
        $categoriesBefore = Category::all()->count();
        $response = $this->post('/pracownik/kategorie', ['name' => $anotherCategory->name]);
        $response->assertStatus(409);
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $content);
        $categoriesAfter = Category::all()->count();
        $this->assertEquals($categoriesBefore, $categoriesAfter);
        $anotherCategory->delete();
        $admin->delete();
    }
}
