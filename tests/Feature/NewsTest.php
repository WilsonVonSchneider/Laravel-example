<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests whether the News::getEverything() function returns a successful response.
     */
    public function test_successful_response_from_getEverything_function(): void
    {
        // Create a new user using the User model factory
        $user = User::factory()->create();

        // Call the getEverything function of the News class with appropriate parameters
        $response = News::getEverything('news', null, null, null, null, null, $user->language, null, 4, null);

        // Assert that the response status is 'ok'
        $this->assertEquals('ok', $response->status);
    }


    // This function tests whether the news page is displayed correctly.
    public function test_news_page_is_displayed(): void
    {
        // Create a new `User` object using a factory method.
        $user = User::factory()->create();

        // Simulate a logged-in user using the `actingAs()` method.
        // This is done to ensure that the test can access the news page,
        // which may require authentication to access.
        $response = $this
            ->actingAs($user)
            ->get('/news');

        // Assert that the HTTP response status code is equal to `200`.
        // This indicates that the news page was successfully displayed.
        $response->assertOk();
    }

    /**
     * Tests that the homepage displays all news for the user.
     */
    public function test_homepage_displays_all_news_for_user(): void
    {
        // Create a user using a factory
        $user = User::factory()->create();

        // Send a GET request to the homepage ('/news') while acting as the user
        $response = $this
            ->actingAs($user)
            ->get('/news');

        // Retrieve the top headlines for the user's language using a custom method 'getEverything'
        $news = News::getEverything('news', null, null, null, null, null, $user->language, null, 4, null);

        // Assert that the response contains the title of each article in news for the user
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

        // Send a GET request to '/news' with the 'page' parameter set to 5
        $response = $this
            ->actingAs($user)
            ->get('/news?page=5');

        // Retrieve the news for the user's language for page 5 using a custom method 'getEverything'
        $news = News::getEverything('news', null, null, null, null, null, $user->language, null, 4, 5);

        // Assert that the response contains the title of each article on the page
        for ($x = 0; $x < 4; $x++) {
            $response->assertSee($news->articles[$x]->title);
        }
    }


    /**
     * Tests that displaying articles using the 'domains' parameter displays matching articles.
     */
    public function test_domains_displays_matching_articles(): void
    {
        // Create a user using a factory
        $user = User::factory()->create();

        // Send a GET request to '/news' with the 'domain' parameter set
        $response = $this
            ->actingAs($user)
            ->get('/news?domain=bbc.co.uk');

        // Retrieve news for the user's language and the 'bbc.co.uk' domain using a custom method 'getEverything'
        $news = News::getEverything('news', null, 'bbc.co.uk', null, null, null, $user->language, null, 4, null);

        // Assert that the response contains the title of the first article in the category
        $response->assertSee($news->articles[0]->title);
    }

    /**
     * Tests that displaying articles using the 'fromDate' and "toDate" parameters display matching articles.
     */
    public function test_from_to_displays_matching_articles(): void
    {
        // Create a user using a factory
        $user = User::factory()->create();

        // Send a GET request to '/news' with the 'fromDate' and "toDate" parameters set
        $response = $this
            ->actingAs($user)
            ->get('/news?fromDate=2023-04-25&toDate=2023-04-27');

        // Retrieve news for the user's language and 'fromDate' and "toDate" parameters using a custom method 'getEverything'
        $news = News::getEverything('news', null, null, null, '2023-04-25', '2023-04-27', $user->language, null, 4, null);

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

        // Send a GET request to '/news' with both 'q' and 'category' parameters set
        $response = $this
            ->actingAs($user)
            ->get('/news?q=news');

        // Retrieve the news that match the search term 'news' for the user's language
        // using a custom method 'getEverything'
        $news = News::getEverything('news', null, null, null, null, null, $user->language, null, 4, null);

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

        // Send a GET request to '/news' with a 'source' parameter set to 'abc-news'
        $response = $this
            ->actingAs($user)
            ->get('/news?source=abc-news');

        // Retrieve news for 'abc-news' using a custom method 'getEverything'
        $news = News::getEverything('news', 'abc-news', null, null, null, null, $user->language, null, 4, null);

        // Assert that the response contains the title of the first article in the 'mtv-news' top headlines
        $response->assertSee($news->articles[0]->title);
    }

    /**
     * Tests that the select input on the news page contains all available sources for the user.
     */
    public function test_sources_select_input_options(): void
    {
        // Create a user using the User factory
        $user = User::factory()->create();

        // Get the news page as the authenticated user
        $response = $this->actingAs($user)->get('/news');

        // Retrieve sources from the News model based on the user's category, language, and country
        $sources = News::getSources($user->category, $user->language, $user->country);

        // Loop through all available sources and assert that each is an option in the select input
        for ($x = 0; $x < count($sources->sources); $x++) {
            $response->assertSee($sources->sources[$x]->id);
            $response->assertSee($sources->sources[$x]->name);
        }
    }

    /**
     * Tests that the select input for sortBy on news page contains all available sortBy for the user.
     */
    public function test_sortBy_select_input_options(): void
    {
        // Create a user using the User factory
        $user = User::factory()->create();

        // Get the news page as the authenticated user
        $response = $this->actingAs($user)->get('/news');

        // Retrieve sortBy from the News model
        $sortBy = News::getSortBy();

        // Loop through all available sortBy and assert that each is an option in the select input
        for ($x = 0; $x < count($sortBy); $x++) {
            $response->assertSee($sortBy[$x]);
        }
    }

    /**
     * Tests whether a "no news found" message is displayed when there are no news items found for the search query.
     */
    public function test_display_no_news_found_message_when_no_results(): void
    {
        // Create a new user using the User model factory
        $user = User::factory()->create();

        // Make a GET request to '/news' endpoint with a search query that has no results
        $response = $this->actingAs($user)->get('/news?q=rgjkrzjertzje');

        // Assert that the response contains a "No news found" message
        $response->assertSee('No news found');
    }
}
