<?php

namespace App\Models;

use jcobhams\NewsApi\NewsApi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model
{

/**
 * @OA\Schema(
 *     schema="News",
 *     type="object",
 *     @OA\Property(
 *         property="status",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="totalResults",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="articles",
 *         type="array",
 *         @OA\Items(
 *              type="object",
 *              @OA\Property(
 *                  property="source",
 *                  type="array",
 *                  @OA\Items(
 *                      type="object",
 *                      @OA\Property(
 *                          property="id",
 *                          type="string"
 *                      ),
 *                      @OA\Property(
 *                          property="name",
 *                          type="string"
 *                      )
 *                  )
 *              ),
 *              @OA\Property(
 *                  property="author",
 *                  type="string"
 *              ),
 *              @OA\Property(
 *                  property="title",
 *                  type="string"
 *              ),
 *              @OA\Property(
 *                  property="description",
 *                  type="string"
 *              ),
 *              @OA\Property(
 *                  property="url",
 *                  type="string"
 *              ),
 *              @OA\Property(
 *                  property="urlToImage",
 *                  type="string"
 *              ),
 *              @OA\Property(
 *                  property="publishedAt",
 *                  type="string"
 *              ),
 *              @OA\Property(
 *                  property="content",
 *                  type="string"
 *              )
 *            )
 *         )
 *     )
 * )
 */
    use HasFactory;

    /* In this model, a PHP client, for News API is used:
        * https://github.com/jcobhams/newsapi-php
        * Install the packege with: composer require jcobhams/newsapi
        * getEverything() returns millions of articles from over 80,000 large and small news sources and blogs
        * Parameters for getEverything($q, $sources, $domains, $exclude_domains, $from, $to, $language, $sort_by,  $page_size, $page):
            * $q : Keywords or a phrase to search for.
            * $sources: A comma-seperated string of identifiers for the news sources or blogs you want headlines from.
                Use the getSources() method to locate these programmatically or look at the sources index.
                Note: you can't mix this param with the country or category params.
            * $domains: A comma-seperated string of domains (eg bbc.co.uk, techcrunch.com, engadget.com) to restrict the search to.
            * $exclude_domains: A comma-seperated string of domains (eg bbc.co.uk, techcrunch.com, engadget.com) to remove from the results.
            * $from: A date and optional time for the oldest article allowed.
                This should be in ISO 8601 format (e.g. 2018-11-16 or 2018-11-16T16:19:03)
                Default: the oldest according to your plan.
            * $to: A date and optional time for the newest article allowed.
                This should be in ISO 8601 format (e.g. 2018-11-16 or 2018-11-16T16:19:03)
                Default: the newest according to your plan.
            * $language: The 2-letter ISO-639-1 code of the language you want to get headlines for.
                Possible options: ar de en es fr he it nl no pt ru se ud zh .
                Default: all languages returned. Use the getLanguages() method to locate these programmatically.
            * $sort_by: The order to sort the articles in. Use the getSortBy() method to locate these programmatically.
            * $page_size: The number of results to return per page (request). 20 is the default, 100 is the maximum.
            * $page: Use this to page through the results if the total results found is greater than the page size.
        * getSources() returns all available sources for all news
        * getSortBy() returns allowed sort_by for all news
        * getLanguages() returns all avaliable languages for all news
    */

    /* getEverything() returns all news*/
    public static function getEverything($q, $sources, $domains, $exclude_domains, $from, $to, $language, $sort_by,  $page_size, $page){
        $newsapi = new NewsApi(config('global.NEWS_API_KEY'));
        $everything = $newsapi->getEverything($q, $sources, $domains, $exclude_domains, $from, $to, $language, $sort_by,  $page_size, $page);
        return ($everything);
    }

    /* getSources() returns all available sources for top headline news*/
    public static function getSources($category, $language, $country){
        $newsapi = new NewsApi(config('global.NEWS_API_KEY'));
        $sources = $newsapi->getSources($category, $language, $country);
        return ($sources);
    }

     /* getSortBy() returns allowed sort_by for all news */
     public static function getSortBy(){
        $newsapi = new NewsApi(config('global.NEWS_API_KEY'));
        $sort_by = $newsapi->getSortBy();
        return ($sort_by);
    }

    /* getLanguages() returns all avaliable languages for all news */
    public static function getLanguages(){
        $newsapi = new NewsApi(config('global.NEWS_API_KEY'));
        $languages = $newsapi->getLanguages();
        return ($languages);
    }
}
