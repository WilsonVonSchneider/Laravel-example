<?php

namespace App\Models;

use jcobhams\NewsApi\NewsApi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TopHeadline extends Model
{
/**
 * @OA\Schema(
 *     schema="TopHeadline",
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

  /*    In this model, a PHP client, for News API is used:
        * https://github.com/jcobhams/newsapi-php
        * Install the packege with: composer require jcobhams/newsapi
        * getTopHeadlines() returns all top headline news
        * Parameters for getTopHeadlines($q, $sources, $country, $category, $page_size, $page);:
            * $q : Keywords or a phrase to search for.
            * $sources: A comma-seperated string of identifiers for the news sources or blogs you want headlines from.
                Use the getSources() method to locate these programmatically or look at the sources index.
                Note: you can't mix this param with the country or category params.
            * $country: The 2-letter ISO 3166-1 code of the country you want to get headlines for.
                Use the getCountries() method to locate these programmatically.
                Note: you can't mix this param with the sources param.
            * $category: The category you want to get headlines for. Use the getCategories() method to locate these programmatically.
                Note: you can't mix this param with the sources param.
            * $page_size: The number of results to return per page (request). 20 is the default, 100 is the maximum.
            * $page: Use this to page through the results if the total results found is greater than the page size.
        * getSources() returns all available sources for top headline news
        * getCountries() returns all avaliable countries for top headline news
        * getCategories() returns all avaliable categories for top headline news */


    // getTopHeadlines() returns all top headline news
    public static function getTopHeadlines($q, $sources, $country, $category, $page_size, $page){
        $newsapi = new NewsApi(config('global.NEWS_API_KEY'));
        $allTopHeadlines=$newsapi->getTopHeadlines($q, $sources, $country, $category, $page_size, $page);
        return ($allTopHeadlines);
    }

    // getSources() returns all available sources for top headline news
    public static function getSources($category, $language, $country){
        $newsapi = new NewsApi(config('global.NEWS_API_KEY'));
        $sources = $newsapi->getSources($category, $language, $country);
        return ($sources);
    }

     // getCountries() returns all avaliable countries for top headline news
     public static function getCountries(){
        $newsapi = new NewsApi(config('global.NEWS_API_KEY'));
        $countries = $newsapi->getCountries();
        return ($countries);
    }

    // getCategories() returns all avaliable categories for top headline news
    public static function getCategories(){
        $newsapi = new NewsApi(config('global.NEWS_API_KEY'));
        $categories = $newsapi->getCategories();
        return ($categories);
    }
}
