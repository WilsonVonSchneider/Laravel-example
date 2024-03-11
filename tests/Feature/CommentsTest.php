<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserComment;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentsTest extends TestCase
{
    use RefreshDatabase; // Use database transactions to reset the database after each test

    public function test_a_comment_can_be_created()
    {
        // Create a user to associate with the comment
        $user = User::factory()->create();

        // Create test data for the comment
        $commentData = [
            'commentText' => 'Test comment',
            'userId' => $user->id,
            'url' => 'text.com'
        ];

        // Send a POST request to the store method with the test data
        $response = $this->actingAs($user)->post(route('comments.store'), $commentData);

        // Assert that the comment was created in the database
        $this->assertDatabaseHas('user_comments', [
            'commentText' => 'Test comment',
            'userId' => $user->id,
            'url' => 'text.com'
        ]);

        // Assert that the user was redirected back to the previous page
        $response->assertRedirect()->assertSessionHas('message', 'Comment successfully added!');
    }

    public function test_a_comment_requires_a_comment_text()
    {
        // Create a user to associate with the comment
        $user = User::factory()->create();

        // Create test data for the comment without a commentText field
        $commentData = [
            'userId' => 1,
            'url' => 'http://example.com'
        ];

        // Send a POST request to the store method with the invalid test data
        $response = $this->actingAs($user)->post(route('comments.store'), $commentData);

        // Assert that the comment was not created in the database
        $this->assertDatabaseCount('user_comments', 0);

        // Assert that the user was redirected back to the previous page with an error message
        $response->assertSessionHasErrors('commentText');
    }

    public function test_comment_can_be_deleted()
    {
        // Create a user to associate with the comment
        $user = User::factory()->create();

        // Create a comment in the database
        $comment = UserComment::factory()->create([
            'userId' => $user->id
        ]);

        // Send a delete request to the `destroy` method with the comment ID
        $response = $this->actingAs($user)->delete(route('comments.destroy', $comment->id));

        // Assert that the comment was deleted from the database
        $this->assertDatabaseMissing('user_comments', ['id' => $comment->id]);

        // Assert that the response redirects back to the previous page
        $response->assertRedirect()->assertSessionHas('message', 'Comment successfully deleted!');
    }

    public function test_comment_can_be_updated()
    {
         // Create a user to associate with the comment
         $user = User::factory()->create();

         // Create a comment in the database
         $comment = UserComment::factory()->create([
             'userId' => $user->id
         ]);

        // Send a put request to the `update` method with the comment ID and new comment text
        $response = $this->actingAs($user)->put(route('comments.update', $comment->id), [
            'commentText' => 'Updated comment text'
        ]);

        // Assert that the comment was updated in the database
        $this->assertDatabaseHas('user_comments', [
            'id' => $comment->id,
            'commentText' => 'Updated comment text'
        ]);

        // Assert that the response redirects back to the previous page
        $response->assertRedirect()->assertSessionHas('message', 'Comment successfully updated!');
    }

    public function test_edit_comment_index_returns_http_ok()
    {

        // Create a new user
        $user = User::factory()->create();

        // Create a comment in the database
        $comment = UserComment::factory()->create([
            'userId' => $user->id
        ]);

        // Send a GET request to the favourites index page
        $response = $this->actingAs($user)->get(route('comments.edit', $comment->id));

        // Assert that the response status code is 200
        $response->assertOk();
    }

}
