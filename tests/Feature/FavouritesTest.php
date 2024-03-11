<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserFavourite;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavouritesTest extends TestCase
{
    use RefreshDatabase;

    public function test_favourites_index_returns_http_ok()
    {

        // Create a new user
        $user = User::factory()->create();

        // Send a GET request to the favourites index page
        $response = $this->actingAs($user)->get(route('favourites.index'));

        // Assert that the response status code is 200
        $response->assertOk();
    }

    public function test_display_no_favourites_message_when_no_favourites_exist()
    {

        // Create a new user
        $user = User::factory()->create();

        // Send a GET request to the favourites index page
        $response = $this->actingAs($user)->get(route('favourites.index'));

        // Assert that there is a message indicating no favourites if there are none
        $response->assertSee('No favourites found');
    }

    public function test_display_favourite_articles_when_favourite_articles_exist()
    {
        // Create a new user
        $user = User::factory()->create();

        // Create 5 favourite items for the user
        UserFavourite::factory(5)->create([
            'userId' => $user->id
        ]);

        // Get all Favourites
        $favourites = UserFavourite::all();

        // Send a GET request to the favourites index page
        $response = $this->actingAs($user)->get(route('favourites.index'));

        // Assert that the response contains the title of the first favourite item in the database
        $response->assertSee($favourites[0]->title);
    }


    public function test_pagination_for_favourites()
    {
        // Define the number of favourites per page
        $perPage = 4;

        // Create a new user
        $user = User::factory()->create();

        // Create 5 favourite items for the user
        UserFavourite::factory($perPage + 1)->create([
            'userId' => $user->id
        ]);

        // Send a GET request to the favourites index page
        $response = $this->actingAs($user)->get(route('favourites.index', ['perPage' => $perPage]));

        // Assert that the response status code is 200
        $response->assertOk();

        // Assert that the correct number of favourites are displayed on the page
        $response->assertSeeInOrder(['Showing', '1', 'to', $perPage, 'of', $perPage + 1, 'results']);

        // Click on the "Next" page link
        $response = $this->actingAs($user)->get(route('favourites.index', ['page' => 2, 'perPage' => $perPage]));

        // Assert that the response status code is 200
        $response->assertOk();

        // Assert that the correct number of favourites are displayed on the second page
        $response->assertSeeInOrder(['Showing', $perPage + 1, 'to', $perPage + 1, 'of', $perPage + 1, 'results']);
    }

    public function test_store_method_creates_new_favourite_item()
    {
        // Create a new user
        $user = User::factory()->create();

        // Send a POST request to the store method with data
        $response = $this->actingAs($user)->post(route('favourites.store'), [
            'title' => 'New Favourite',
            'url' => 'https://example.com/new-favourite',
            'author' => 'John Doe',
            'description' => 'This is a new favourite item.',
            'image' => 'https://example.com/image.jpg',
            'userId' => $user->id
        ]);

        // Assert that the response status code is 302
        $response->assertStatus(302);

        // Assert that the new favourite item was created in the database
        $this->assertDatabaseHas('user_favourites', [
            'title' => 'New Favourite',
            'url' => 'https://example.com/new-favourite',
            'author' => 'John Doe',
            'description' => 'This is a new favourite item.',
            'imageUrl' => 'https://example.com/image.jpg',
            'userId' => $user->id
        ]);
    }

    public function test_destroy_deletes_favourite()
    {
        // Create a new user
        $user = User::factory()->create();

        // Create a new favourite item for the user
        $favourite = UserFavourite::factory()->create([
            'userId' => $user->id
        ]);

        // Send a DELETE request to the delete route for the favourite item
        $response = $this->actingAs($user)->delete(route('favourites.destroy', $favourite->id));

        // Assert that the response status code is 302 (redirect)
        $response->assertStatus(302);

        // Assert that the favourite item has been deleted
        $this->assertNull(UserFavourite::find($favourite->id));
    }
}
