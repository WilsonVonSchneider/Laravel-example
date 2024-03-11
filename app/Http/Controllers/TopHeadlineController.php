<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TopHeadline;
use App\Models\UserComment;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Top Headlines"
 * )
 */

class TopHeadlineController extends Controller
{
        /**
         * @OA\Get(
         *     path="/topHeadlines",
         *     summary="Get top headlines",
         *     description="Return a list of top headlines from various sources with pagination and filtering options",
         *     operationId="topHeadlines.index",
         *     tags={"Top Headlines"},
         *     @OA\Parameter(
         *         name="q",
         *         in="query",
         *         description="Keywords or a phrase to search for in the article title and body",
         *         required=false,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *     @OA\Parameter(
         *         name="sources",
         *         in="query",
         *         description="A comma-seperated string of identifiers for the news sources or blogs you want headlines from",
         *         required=false,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *     @OA\Parameter(
         *         name="country",
         *         in="query",
         *         description="The 2-letter ISO 3166-1 code of the country you want to get headlines for",
         *         required=false,
         *         @OA\Schema(
         *             type="string",
         *             enum={"ae", "ar", "at", "au", "be", "bg", "br", "ca", "ch", "cn", "co", "cu", "cz", "de", "eg", "fr", "gb", "gr", "hk", "hu", "id", "ie", "il", "in", "it", "jp", "kr", "lt", "lv", "ma", "mx", "my", "ng", "nl", "no", "nz", "ph", "pl", "pt", "ro", "rs", "ru", "sa", "se", "sg", "si", "sk", "th", "tr", "tw", "ua", "us", "ve", "za"}
         *         )
         *     ),
        *      @OA\Parameter(
        *          name="category",
        *          in="query",
        *          description="The category you want to get headlines for",
        *          required=false,
        *          @OA\Schema(
        *              type="string",
        *              enum={"business", "entertainment", "general", "health", "science", "sports", "technology"}
        *          )
        *      ),
        *      @OA\Parameter(
        *          name="page_size",
        *          in="query",
        *          description="The number of results to return per page (request). 20 is the default, 100 is the maximum.",
        *          required=false,
        *          @OA\Schema(
        *              type="integer",
        *              default=20
        *          )
        *      ),
        *      @OA\Parameter(
        *          name="page",
        *          in="query",
        *          description="Use this to page through the results if the total results found is greater than the page size.",
        *          required=false,
        *          @OA\Schema(
        *              type="integer",
        *              default=1
        *          )
        *      ),
        *      @OA\Response(
        *          response=200,
        *          description="Successful operation",
        *          @OA\JsonContent(
        *              type="array",
        *              @OA\Items(ref="#/components/schemas/TopHeadline")
        *          )
        *      ),
        *      @OA\Response(
        *          response=404,
        *          description="Not found"
        *      ),
        *      @OA\Response(
        *          response=500,
        *          description="Internal server error"
        *      )
        *  )
        */

    public function index(Request $request)
    {

        // Initializing variables to default values
        $page = null;
        $q = null;
        $perPage = 4;
        $category = auth()->user()->category;
        $country = auth()->user()->country;
        $language = auth()->user()->language;
        $source = null;
        $url = url()->current();

        // Extracting query parameters from the URL
        extract($request->all());

        // If there are query parameters, overwrite the initial values with values extracted from the URL
        if (extract($request->all())) {
            $page = (int)$page;
            $q = $q;
            $category = $category;
            $source = $source;
        }

        // If there is a 'source' parameter, overwrite the 'category' and 'country' parameters with null, because the 'source' parameter can not be mixed with the 'country' or 'category' parameters
        if ($source) {
            $category = null;
            $country = null;
        }

        // Get all sources for the view (to be used in filter section)
        $allSources = TopHeadline::getSources($category, $language, $country);

        // Get all categories for the view (to be used for category selection)
        $allCategories = TopHeadline::getCategories();

        // Get top headline news for the view (to be used for showing the top headline news)
        $topHeadlines = TopHeadline::getTopHeadlines($q, $source, $country, $category, $perPage, $page);

        // Get all comments for the view (to be used to show comments for each topHeadlines news)
        $comments=UserComment::with('user')->get();

        // Define functionality for matching URLs for favourites.
        $arrayOfMatchedUrls = [];

        // Retrieve all favourites for the currently authenticated user.
        $allFavourites = User::with('favourites')->where('id', 'like', auth()->user()->id)->get();

        // Loop through each of the user's favourites and compare them against the top news articles.
        for ($y = 0; $y < count($allFavourites[0]['favourites']); $y++) {
            for ($x = 0; $x < count($topHeadlines->articles); $x++) {
                // If the favourite URL matches the news article URL, add it to the array of matched URLs.
                if ($topHeadlines->articles[$x]->url == $allFavourites[0]['favourites'][$y]['url']) {
                    $arrayOfMatchedUrls[$allFavourites[0]['favourites'][$y]['url']] = $topHeadlines->articles[$x]->url;
                }
            }
        }
        // Array of matched URLs will be used in the view to hide "Add to favourites" button with array_search() function

        // Calculate the number of pages, to be used for pagination
        $numberOfPages = $topHeadlines->totalResults / $perPage;
        $numberOfPages = ceil($numberOfPages);

        // Limit the number of pages that can be shown on the page (the API has a limit of 100 news per request, so this logic avoids exceeding that limit and receiving an error)
        if ($numberOfPages > ceil(100 / $perPage)) {
            $numberOfPages = floor(100 / $perPage);
        }

        // Return the view with all necessary data
        return view('topHeadlines.index', [
            'newsTop' => $topHeadlines->articles,
            'q' => $q,
            'allSources' => $allSources->sources,
            'source' => $source,
            'category' => $category,
            'categoriesAll' => $allCategories,
            'numberOfPages' => $numberOfPages,
            'currentPage' => $page,
            'url' => $url,
            'arrayOfMatchedUrls' => $arrayOfMatchedUrls,
            'comments' => $comments
        ]);
    }
}
