<?php

namespace App\Http\Controllers;


use App\Models\UserLog;
use App\Models\UserComment;
use Illuminate\Http\Request;
use App\Models\UserFavourite;

/**
 * @OA\Tag(
 *     name="User Favourites",
 *     description="Endpoints for managing user's favourite news"
 * )
 */

  /**
 * @OA\Schema(
 *     schema="UserFavouriteInput",
 *     type="object",
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         example="My favourite news"
 *     ),
 *     @OA\Property(
 *         property="url",
 *         type="string",
 *         example="https://example.com/news/123"
 *     ),
 *     @OA\Property(
 *         property="author",
 *         type="string",
 *         example="John Doe"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         example="This is my favourite news"
 *     ),
 *     @OA\Property(
 *         property="imageUrl",
 *         type="string",
 *         example="https://example.com/image.jpg"
 *     ),
 *     @OA\Property(
 *         property="userId",
 *         type="string",
 *         example="93504d6a-e476-48d8-be86-df361cf2c4bb"
 *     )
 * )
 */

class UserFavouriteController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *      path="/favourites",
     *      operationId="getFavourites",
     *      tags={"User Favourites"},
     *      summary="Get list of user's favourite news",
     *      description="Returns list of user's favourite news",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *      ),
     *      @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/UserFavourite")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *      )
     * )
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */

    public function index(Request $request)
    {
        // Initializing variables to default values
        $page = null;
        $perPage = 4;

        // Extracting the page value from the request parameters, if present
        if (extract($request->all())) {
            $page = $page;
            $perPage = $perPage;
        }

        // Retrieving the user's favourite items, ordered by creation date in descending order, and paginating the results.
        $favourites = UserFavourite::where('userId', 'like', auth()->user()->id)->orderBy('created_at', 'desc')->paginate($perPage);

        // Get all comments for the view (to be used to show comments for each news)
        $comments=UserComment::with('user')->get();

        // If there are no favourite items to display, redirect to the previous page.
        if ($favourites->isEmpty() && $page > 1) {
            return redirect()->route('favourites.index', ['page' => $page - 1, 'perPage' => $perPage]);
        }

        // If there are favourite items to display, return the view for the user's favourite items.
        return view('favourites.index', [
            'favourites' => $favourites,
            'perPage' => $perPage,
            'comments' => $comments
        ]);
    }

      /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *      path="/favourites",
     *      operationId="addFavourite",
     *      tags={"User Favourites"},
     *      summary="Add a news to user's favourites",
     *      description="Add a news to user's favourites",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Request body for adding a favourite",
     *          @OA\JsonContent(ref="#/components/schemas/UserFavouriteInput")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Favourite successfully added",
     *          @OA\JsonContent(ref="#/components/schemas/UserFavourite")
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *      )
     * )
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store(Request $request)
    {
        // Create a new favourite item with the data submitted in the request
        $favourite=UserFavourite::create([
            'title' => $request->title,
            'url' => $request->url,
            'author' => $request->author,
            'description' => $request->description,
            'imageUrl' => $request->image,
            'userId' => $request->userId

        ]);

        //Logging the action
        $log['action']='favourited';
        $log['description']=$favourite->title .= ' - news favourited';
        $log['userId']=auth()->user()->id;
        UserLog::create($log);

        // Redirect back to the previous page
        return redirect()->back()->with('message', 'Favourite successfully added!');
    }

    /**
 * @OA\Delete(
 *     path="/favourites/{id}",
 *     summary="Delete a favourite item",
 *     description="Deletes a favourite item with the specified ID.",
 *     operationId="deleteFavourite",
 *     tags={"User Favourites"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the favourite item to delete",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *             format="uuid",
 *             example="a110d11b-176a-4813-8c31-a69f9f50f7fc"
 *         )
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Favourite item successfully deleted"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Favourite item not found"
 *     ),
 *
 *     @OA\Response(
 *          response=500,
 *          description="Internal server error",
 *      )
 * )
 */

    public function destroy($id)
    {
        // Find the favourite item by its ID
        $favourite = UserFavourite::find($id);

        // Delete the favourite item from the database
        $favourite->delete();

         //Logging the action
         $log['action']='delete_favourite';
         $log['description']=$favourite->title .= ' - news removed from favourites';
         $log['userId']=auth()->user()->id;
         UserLog::create($log);

        // Redirect the user back to the previous page
        return redirect()->back()->with('message', 'Favourite successfully removed!');
    }


}
