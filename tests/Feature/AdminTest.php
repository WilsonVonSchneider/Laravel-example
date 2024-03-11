<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_index_returns_http_ok_for_admin_user()
    {

        // Create a new user
        $user = User::factory()->create([
            'role' => true
        ]);

        // Send a GET request to the admin index page
        $response = $this->actingAs($user)->get(route('admin.index'));

        // Assert that the response status code is 200
        $response->assertOk();
    }


    public function test_admin_index_redirects_for_non_admin_user()
    {

        // Create a new user
        $user = User::factory()->create([
            'role' => false
        ]);

        // Send a GET request to the admin index page
        $response = $this->actingAs($user)->get(route('admin.index'));

        // Assert that the response status code is 302
        $response->assertStatus(302);
    }

    public function test_admin_index()
    {
        // Create an admin user
        $admin = User::factory()->create(['role' => true]);

        // Create a normal user
        $users = User::factory(6)->create(['role' => false]);

        // Test as an admin user
        $response = $this->actingAs($admin)->get(route('admin.index'));
        $response->assertViewIs('admin.index'); // Should use the admin index view
        $response->assertViewHas('users'); // Should pass the users variable to the view
        $response->assertSee($admin->name);
        $response->assertSee($users[0]->name);
    }

    public function test_search_for_admin_index()
    {
        // Create an admin user
        $admin = User::factory()->create(['role' => true, 'name' => 'Admin Skywalker']);

        // Create 10 users
        $users = User::factory(8)->create(['role' => false]);

        // Make a request to the index page with a search parameter
        $response = $this->actingAs($admin)->get('/admin/users?search=Admin');

        // Assert that we see the first user on page 2
        $response->assertSee($admin->name);
    }


    public function test_admin_show_returns_http_ok_for_admin_user()
    {

        // Create a new user
        $user = User::factory()->create([
            'role' => true
        ]);

        // Send a GET request to the user show page
        $response = $this->actingAs($user)->get(route('admin.show', $user->id));

        // Assert that the response status code is 200
        $response->assertOk();
    }

    public function test_admin_show_redirects_for_non_admin_user()
    {
        // Create a new user
        $user = User::factory()->create([
            'role' => false
        ]);

        // Send a GET request to the user show page
        $response = $this->actingAs($user)->get(route('admin.show', $user->id));

        // Assert that the response status code is 302
        $response->assertStatus(302);
    }

    public function test_admin_show_shows_data_of_user()
    {
        // Create a new user
        $user = User::factory()->create([
            'role' => true
        ]);

        // Send a GET request to the user show page
        $response = $this->actingAs($user)->get(route('admin.show', $user->id));

        // Assert that we see the user email
        $response->assertSee($user->email);

        // Assert that we see the user name
        $response->assertSee($user->name);

        // Assert that we see the user category
        $response->assertSee($user->category);

        // Assert that we see the user language
        $response->assertSee($user->language);

        // Assert that we see the user country
        $response->assertSee($user->country);
    }

    public function test_admin_users_favourites_returns_http_ok_for_admin_user()
    {

        // Create a new user
        $user = User::factory()->create([
            'role' => true
        ]);

        // Send a GET request to the users favourites page
        $response = $this->actingAs($user)->get(route('admin.users.favourites', $user->id));

        // Assert that the response status code is 200
        $response->assertOk();
    }

    public function test_admin_users_favourites_redirects_for_non_admin_user()
    {

        // Create a new user
        $user = User::factory()->create([
            'role' => false
        ]);

        // Send a GET request to the users favourites page
        $response = $this->actingAs($user)->get(route('admin.users.favourites', $user->id));

        // Assert that the response status code is 302
        $response->assertStatus(302);
    }

    public function test_admin_users_comments_returns_http_ok_for_admin_user()
    {

        // Create a new user
        $user = User::factory()->create([
            'role' => true
        ]);

        // Send a GET request to the users comments page
        $response = $this->actingAs($user)->get(route('admin.users.comments', $user->id));

        // Assert that the response status code is 200
        $response->assertOk();
    }

    public function test_admin_users_comments_redirects_for_non_admin_user()
    {

        // Create a new user
        $user = User::factory()->create([
            'role' => false
        ]);

        // Send a GET request to the users comments page
        $response = $this->actingAs($user)->get(route('admin.users.comments', $user->id));

        // Assert that the response status code is 302
        $response->assertStatus(302);
    }

    public function test_admin_users_logs_returns_http_ok_for_admin_user()
    {

        // Create a new user
        $user = User::factory()->create([
            'role' => true
        ]);

        // Send a GET request to the users logs page
        $response = $this->actingAs($user)->get(route('admin.users.logs', $user->id));

        // Assert that the response status code is 200
        $response->assertOk();
    }

    public function test_admin_users_logs_redirects_for_non_admin_user()
    {

        // Create a new user
        $user = User::factory()->create([
            'role' => false
        ]);

        // Send a GET request to the users logs page
        $response = $this->actingAs($user)->get(route('admin.users.logs', $user->id));

        // Assert that the response status code is 302
        $response->assertStatus(302);
    }


}
