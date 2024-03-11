<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\User;
use App\Models\UserComment;
use Illuminate\Http\Request;

/**
    * @OA\Tag(
    *     name="News",
    * )
*/
class NewsController extends Controller
{
    /**
    * @OA\Get(
    *     path="/news",
    *     summary="Get all news",
    *     description="Return a list of all news from various sources with pagination and filtering options",
    *     operationId="news.index",
    *     tags={"News"},
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
    *         name="exclude_domains",
    *         in="query",
    *         description="A comma-seperated string of domains (eg bbc.co.uk, techcrunch.com, engadget.com) to remove from the results",
    *         required=false,
    *         @OA\Schema(
    *             type="string"
    *         )
    *     ),
    *     @OA\Parameter(
    *         name="domains",
    *         in="query",
    *         description="A comma-seperated string of domains (eg bbc.co.uk, techcrunch.com, engadget.com) to restrict the search to",
    *         required=false,
    *         @OA\Schema(
    *             type="string"
    *         )
    *     ),
    *     @OA\Parameter(
    *         name="from",
    *         in="query",
    *         description="A date and optional time for the oldest article allowed. This should be in ISO 8601 format (e.g. 2018-11-16 or 2018-11-16T16:19:03)",
    *         required=false,
    *         @OA\Schema(
    *             type="string"
    *         )
    *     ),
    *      @OA\Parameter(
    *          name="to",
    *          in="query",
    *          description="A date and optional time for the newest article allowed. This should be in ISO 8601 format (e.g. 2018-11-16 or 2018-11-16T16:19:03)",
    *          required=false,
    *          @OA\Schema(
    *              type="string",
    *          )
    *      ),
    *       @OA\Parameter(
    *          name="language",
    *          in="query",
    *          description="he 2-letter ISO-639-1 code of the language you want to get headlines for",
    *          required=false,
    *          @OA\Schema(
    *              type="string",
    *              enum={"ar", "de", "en", "es", "fr", "he", "it", "nl", "no", "pt", "ru", "se", "ud", "zh"}
    *          )
    *      ),
    *      @OA\Parameter(
    *          name="sort_by",
    *          in="query",
    *          description="The order to sort the articles in. Use the getSortBy() method to locate these programmatically",
    *          required=false,
    *          @OA\Schema(
    *              type="string",
    *              enum={"relevancy", "popularity", "publishedAt"}
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
    *              @OA\Items(ref="#/components/schemas/News")
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
    public function index(Request $request){

    // Initializing variables to default values
    $page=null;
    $q='news';
    $perPage=4;
    $language=auth()->user()->language;
    $category=auth()->user()->category;
    $country=auth()->user()->news_country;
    $fromDate=null;
    $toDate=null;
    $domains=null;
    $sortBy=null;
    $source=null;
    $url = url()->current();

    // Extracting query parameters from the URL
    extract($request->all());

    // If there are query parameters, overwrite the initial values with values extracted from the URL
    if(extract($request->all())){
        if($page){
            $page = (int)$page;
        }
        $q = $q;
        $category = $category;
        $fromDate=$fromDate;
        $toDate=$toDate;
        $domains=$domains;
        $sortBy=$sortBy;
        $source=$source;
    }

    // Get all sources for the view (to be used in filter section)
    $allSources=News::getSources($category, $language, $country);

    // Get all sortBy for the view (to be used for sortBy filter)
    $allSortBy=News::getSortBy();

    // Get top all news for the view (to be used for showing all news)
    $paginated=News::getEverything($q, $source, $domains, null, $fromDate, $toDate, $language, $sortBy, $perPage, $page);

    // Get all comments for the view (to be used to show comments for each news)
    $comments=UserComment::with('user')->get();

    // Define functionality for matching URLs for favourites.
    $arrayOfMatchedUrls = [];

    // Retrieve all favourites for the currently authenticated user.
    $allFavourites = User::with('favourites')->where('id', 'like', auth()->user()->id)->get();

    // Loop through each of the user's favourites and compare them against the top news articles.
    for ($y = 0; $y < count($allFavourites[0]['favourites']); $y++) {
        for ($x = 0; $x < count($paginated->articles); $x++) {
            // If the favourite URL matches the news article URL, add it to the array of matched URLs.
            if ($paginated->articles[$x]->url == $allFavourites[0]['favourites'][$y]['url']) {
                $arrayOfMatchedUrls[$allFavourites[0]['favourites'][$y]['url']] = $paginated->articles[$x]->url;
            }
        }
    }

    // Calculate the number of pages, to be used for pagination
    $numberOfPages=$paginated->totalResults/$perPage;
    $numberOfPages=ceil($numberOfPages);

    // Limit the number of pages that can be shown on the page (the API has a limit of 100 news per request, so this logic avoids exceeding that limit and receiving an error)
    if($numberOfPages>ceil(100/$perPage)){
        $numberOfPages=floor(100/$perPage);
    }

    // Return the view with all necessary data
    return view('news.index', [
        'newsTop'=>$paginated->articles,
        'q'=>$q,
        'source'=>$source,
        'allSources'=>$allSources->sources,
        'fromDate'=>$fromDate,
        'toDate'=>$toDate,
        'domains'=>$domains,
        'allSortBy'=>$allSortBy,
        'sortBy'=>$sortBy,
        'numberOfPages'=>$numberOfPages,
        'currentPage'=>$page,
        'url'=>$url,
        'arrayOfMatchedUrls' => $arrayOfMatchedUrls,
        'comments' => $comments
    ]);
    }
}
