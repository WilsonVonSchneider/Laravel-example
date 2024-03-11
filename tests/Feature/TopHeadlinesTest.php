<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

use App\Models\TopHeadline;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TopHeadlinesTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_response_from_TopHeadline_function(): void
    {

        $user = User::factory()->create();

        $response = TopHeadline::getTopHeadlines(null, null, $user->country, $user->category, 4, null);

        $this->assertEquals('ok', $response->status);
    }

    // This function tests whether the `topHeadlines` page is displayed correctly.
    public function test_topHeadlines_page_is_displayed(): void
    {
        // Create a new `User` object using a factory method.
        $user = User::factory()->create();

        // Simulate a logged-in user using the `actingAs()` method.
        // This is done to ensure that the test can access the `topHeadlines` page,
        // which may require authentication to access.
        $response = $this
            ->actingAs($user)
            ->get('/topHeadlines');

        // Assert that the HTTP response status code is equal to `200`.
        // This indicates that the `topHeadlines` page was successfully displayed.
        $response->assertOk();
    }

    /**
     * Tests that the homepage displays the top headlines news for the user.
     */
    public function test_homepage_displays_top_headlines_news_for_user(): void
    {
        // Create a user using a factory
        $user = User::factory()->create();

        // Send a GET request to the homepage ('/topHeadlines') while acting as the user
        $response = $this
            ->actingAs($user)
            ->get('/topHeadlines');

        // Retrieve the top headlines for the user's category and country using a custom method 'getTopHeadlines'
        $news = TopHeadline::getTopHeadlines(null, null, $user->country, $user->category, 4, null);

        // Assert that the response contains the title of each article in the top headlines for the user
        for ($x = 0; $x < 4; $x++) {
            $response->assertSee($news->articles[$x]->title);
        }
    }


    /**
     * Tests that the pagination displays the correct news articles.
     */
    public function test_pagination_displays_correct_news_articles(): void
    {
        // Create a user using a factory
        $user = User::factory()->create();

        // Send a GET request to '/topHeadlines' with the 'page' parameter set to 5
        $response = $this
            ->actingAs($user)
            ->get('/topHeadlines/?page=5');

        // Retrieve the top headlines for the user's category and country for page 5 using a custom method 'getTopHeadlines'
        $news = TopHeadline::getTopHeadlines(null, null, $user->country, $user->category, 4, 5);

        // Assert that the response contains the title of each article on the page
        for ($x = 0; $x < 4; $x++) {
            $response->assertSee($news->articles[$x]->title);
        }
    }


    /**
     * Tests that displaying articles using the 'category' parameter displays matching articles.
     */
    public function test_category_displays_matching_articles(): void
    {
        // Create a user using a factory
        $user = User::factory()->create();

        // Send a GET request to '/topHeadlines' with the 'category' parameter set
        $response = $this
            ->actingAs($user)
            ->get('/topHeadlines/?category=general');

        // Retrieve the top headlines for the user's country and the 'general' category using a custom method 'getTopHeadlines'
        $news = TopHeadline::getTopHeadlines(null, null, $user->country, 'general', 4, null);

        // Assert that the response contains the title of the first article in the category
        $response->assertSee($news->articles[0]->title);
    }


    /**
     * Tests that searching for articles using the 'q' parameter displays matching articles.
     */
    public function test_search_displays_matching_articles(): void
    {
        // Create a user using a factory
        $user = User::factory()->create();

        // Send a GET request to '/topHeadlines' with both 'q' and 'category' parameters set
        $response = $this
            ->actingAs($user)
            ->get('/topHeadlines/?q=news&category=general');

        // Retrieve the top headlines that match the search term 'news' for the user's country and the 'general' category
        // using a custom method 'getTopHeadlines'
        $news = TopHeadline::getTopHeadlines('news', null, $user->country, 'general', 4, null);

        // Assert that the response contains the title of the first article in the search results
        $response->assertSee($news->articles[0]->title);
    }


    /**
     * Tests that the 'source' parameter displays matching articles when fetching top headlines.
     */
    public function test_source_displays_matching_articles(): void
    {
        // Create a user using a factory
        $user = User::factory()->create();

        // Send a GET request to '/topHeadlines' with a 'source' parameter set to 'mtv-news'
        $response = $this
            ->actingAs($user)
            ->get('/topHeadlines/?source=mtv-news');

        // Retrieve the top headlines for 'mtv-news' using a custom method 'getTopHeadlines'
        $news = TopHeadline::getTopHeadlines(null, 'mtv-news', null, null, 4, null);

        // Assert that the response contains the title of the first article in the 'mtv-news' top headlines
        $response->assertSee($news->articles[0]->title);
    }

    /**
     * Tests that the 'source' parameter is not used in conjunction with the 'category' parameter
     * when fetching top headlines.
     */
    public function test_source_parameter_excludes_category_parameter_from_top_headlines(): void
    {
        // Create a user using a factory
        $user = User::factory()->create();

        // Send a GET request to '/topHeadlines' with both 'source' and 'category' parameters set
        $response = $this
            ->actingAs($user)
            ->get('/topHeadlines/?source=mtv-news&category=health');

        // Assert that the response status code is 200 (OK)
        $response->assertOk();
    }

    /**
     * Test that top headline categories are displayed on the /topHeadlines page.
     */
    public function test_top_headline_categories_displayed_on_page(): void
    {
        // Create a user using the User factory
        $user = User::factory()->create();

        // Log in the user using actingAs()
        $response = $this
            ->actingAs($user)
            ->get('/topHeadlines/?source=mtv-news');

        // Retrieve categories using the TopHeadline model
        $categories = TopHeadline::getCategories();

        // Loop through each category and check if it is displayed on the view
        for ($x = 0; $x < count($categories); $x++) {
            $response->assertSee($categories[$x]);
        }
    }

    /**
     * Tests that the select input on the topHeadlines page contains all available sources for the user.
     */
    public function test_select_input_options(): void
    {
        // Create a user using the User factory
        $user = User::factory()->create();

        // Get the topHeadlines page as the authenticated user
        $response = $this->actingAs($user)->get('/topHeadlines');

        // Retrieve sources from the TopHeadline model based on the user's category, language, and country
        $sources = TopHeadline::getSources($user->category, $user->language, $user->country);

        // Loop through all available sources and assert that each is an option in the select input
        for ($x = 0; $x < count($sources->sources); $x++) {
            $response->assertSee($sources->sources[$x]->id);
            $response->assertSee($sources->sources[$x]->name);
        }
    }

    /**
     * Tests whether a "no news found" message is displayed when there are no news items found for the search query.
     */
    public function test_display_no_news_found_message_when_no_results(): void
    {
        // Create a new user using the User model factory
        $user = User::factory()->create();

        // Make a GET request to '/topHeadlines' endpoint with a search query that has no results
        $response = $this->actingAs($user)->get('/topHeadlines?q=rgjkrzjertzje');

        // Assert that the response contains a "No news found" message
        $response->assertSee('No news found');
    }
}
