<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLog;
use App\Models\UserComment;
use Illuminate\Http\Request;
use App\Models\UserFavourite;

class AdminController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     *
     * @OA\Get(
     *      path="/admin/users",
     *      operationId="index",
     *      tags={"Admin"},
     *      summary="Display a listing of the users",
     *      description="Display a paginated list of all users, or search for users with a name or email that contains the search parameter",
     *      @OA\Parameter(
     *          name="search",
     *          in="query",
     *          description="Search parameter",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *      ),
     *  @OA\Response(
     *          response=404,
     *          description="Not found",
     *      ),
     *  @OA\Response(
     *          response=500,
     *          description="Internal server error"
     *      )
     * )
     */

    public function index()
    {

        // Get search parameter from the request
        $search = request('search');

        // Set number of users to display per page
        $perPage = 7;

        // Get all users from database, ordered by name ascending, and paginate
        $users = User::orderBy('name', 'asc')->paginate($perPage);

        // If a search parameter was provided
        if ($search) {
            // Search for users with a name or email that contains the search parameter, and paginate
            $users = User::where('name', 'ilike', '%%' . $search . '%%')->orWhere('email', 'ilike', '%%' . $search . '%%')->paginate($perPage);
        }

        // Return the admin index view with the users and perPage variables
        return view('admin.index', [
            'users' => $users,
            'perPage' => $perPage
        ]);
    }

    /**
     * @OA\Get(
     *     path="/admin/users/{id}",
     *     summary="Get user by ID",
     *     description="Returns a single user with their favourite items and comments, identified by their ID.",
     *     operationId="getUserById",
     *     tags={"Admin"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="UUID of the user to fetch",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uudi"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User found",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error"
     *      )
     * )
     */


    public function show($id)
    {
        // Retrieve the user with the specified ID and eager load their favourites and comments
        $user = User::with('favourites')->with('comments')->find($id);

        // Return the "admin.show" view with the retrieved user passed as a variable
        return view('admin.show', [
            'user' => $user
        ]);
    }

    /**
     * @OA\Get(
     *     path="/admin/users/{id}/favourites",
     *     summary="Retrieve a user's favourites",
     *     description="Retrieve a paginated list of a user's favourite items, filtered by user ID and ordered by creation date in descending order.",
     *     tags={"Admin"},
     *     @OA\Parameter(
     *         name="id",
     *         description="The UUID of the user whose favourites to retrieve",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="favourites",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/UserFavourite")
     *             ),
     *             @OA\Property(
     *                 property="user",
     *                 ref="#/components/schemas/User"
     *             ),
     *             @OA\Property(
     *                 property="perPage",
     *                 type="integer",
     *                 example=7
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="User not found."
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error"
     *      )
     * )
     */

    public function getFavourites($id)
    {
        // Set the number of items to display per page
        $perPage = 7;

        // Retrieve the user's favourites, filtered by user ID and ordered by creation date in descending order
        $favourites = UserFavourite::where('userId', 'like', $id)->orderBy('created_at', 'desc')->paginate($perPage);

        // Retrieve the user with the specified ID
        $user = User::find($id);

        // Render the 'getFavourites' view, passing in the favourites, user, and per-page values
        return view('admin.getFavourites', [
            'favourites' => $favourites,
            'user' => $user,
            'perPage' => $perPage
        ]);
    }


    /**
     * Get all comments for the user with the specified ID and paginate them.
     *
     * @param int $id The ID of the user whose comments to retrieve
     *
     * @return \Illuminate\View\View The view containing the user's comments
     *
     * @OA\Get(
     *     path="/admin/users/{id}/comments",
     *     summary="Get all comments for a user",
     *     tags={"Admin"},
     *     @OA\Parameter(
     *         name="id",
     *         description="The UUID of the user whose comments to retrieve",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="string",
     *             format="uuid",
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comments retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="comments",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/UserComment"),
     *             ),
     *             @OA\Property(
     *                 property="user",
     *                 ref="#/components/schemas/User",
     *             ),
     *             @OA\Property(
     *                 property="perPage",
     *                 type="integer",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error"
     *      ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */

    public function getComments($id)
    {
        // Define number of items to display per page
        $perPage = 7;

        // Retrieve comments for the user with the specified ID, sorted by creation date
        $comments = UserComment::where('userId', 'like', $id)->orderBy('created_at', 'desc')->paginate($perPage);

        // Retrieve the user with the specified ID
        $user = User::find($id);

        // Return the view containing the comments for the user
        return view('admin.getComments', [
            'comments' => $comments,
            'user' => $user,
            'perPage' => $perPage
        ]);
    }

    /**
     * Retrieve the logs for the specified user ID.
     *
     * @OA\Get(
     *     path="/admin/users/{id}/logs",
     *     summary="Retrieve the logs for the specified user ID.",
     *     description="Retrieve the logs for the specified user ID.",
     *     operationId="getLogs",
     * tags={"Admin"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The UUID of the user whose logs to retrieve.",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Schema(
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/UserLog")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error"
     *      )
     * )
     */

    public function getLogs($id)
    {
        // Mapping of action types to color codes for display
        $actionColors = [
            'favourited' => 'blue',
            'commented' => 'green',
            'update' => 'gray',
            'delete_comment' => 'red',
            'delete_favourite' => 'indigo',
        ];

        // Get the 'action' query parameter
        $action = request('action');

        // Set the number of results to display per page
        $perPage = 7;

        // Get the 'fromDate' and 'toDate' query parameters
        $fromDate = request('fromDate');
        $toDate = request('toDate');

        // If both 'fromDate' and 'toDate' are provided, filter logs by date range
        if ($fromDate && $toDate) {
            if ($action) {
                $logs = UserLog::where('userId', 'like', $id)
                    ->where('action', 'like', $action)
                    ->whereBetween('created_at', [$fromDate, $toDate])
                    ->orderBy('created_at', 'desc')
                    ->paginate($perPage);
            } else {
                $logs = UserLog::where('userId', 'like', $id)
                    ->orderBy('created_at', 'desc')
                    ->whereBetween('created_at', [$fromDate, $toDate])
                    ->paginate($perPage);
            }
        } else { // If either 'fromDate' or 'toDate' is missing, display logs without filtering by date
            if ($action) {
                $logs = UserLog::where('userId', 'like', $id)
                    ->where('action', 'like', $action)
                    ->orderBy('created_at', 'desc')
                    ->paginate($perPage);
            } else {
                $logs = UserLog::where('userId', 'like', $id)
                    ->orderBy('created_at', 'desc')
                    ->paginate($perPage);
            }
        }

        // Get the user object with the given ID
        $user = User::find($id);

        // Return the 'getLogs' view with the following variables passed to it
        return view('admin.getLogs', [
            'logs' => $logs, // The filtered logs to display
            'user' => $user, // The user object for the current user
            'perPage' => $perPage, // The number of results to display per page
            'actionColors' => $actionColors, // Mapping of action types to color codes
            'action' => $action // The currently selected action filter
        ]);
    }
}
