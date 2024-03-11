<?php

namespace App\Http\Controllers;

use App\Models\UserLog;
use App\Models\UserComment;
use Illuminate\Http\Request;

/**
    * @OA\Tag(
    *     name="User Comments"
    * )
*/
class UserCommentController extends Controller
{
    /**
    * @OA\Post(
    *     path="/comments",
    *     summary="Create a new comment",
    *     tags={"User Comments"},
    *     @OA\RequestBody(
    *         @OA\JsonContent(
    *             required={"commentText", "userId", "url"},
    *             @OA\Property(property="commentText", type="string"),
    *             @OA\Property(property="userId", type="string"),
    *             @OA\Property(property="url", type="string"),
    *         ),
    *     ),
    *   @OA\Response(
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
    */
    public function store(Request $request)
    {
        // Validate the form fields
        $formFields = $request->validate([
            'commentText' => 'required'
        ]);

        // Add the user ID and URL to the form data
        $formFields['userId'] = $request->userId;
        $formFields['url'] = $request->url;

        // Create a new Comment record in the database
        UserComment::create($formFields);

        //Logging the action
        $log['action']='commented';
        $log['description']=$formFields['commentText'] .= ' - comment added';
        $log['userId']=auth()->user()->id;
        UserLog::create($log);

        // Redirect the user back to the previous page
        return redirect()->back()->with('message', 'Comment successfully added!');
    }

    /**
 * @OA\Delete(
 *     path="/comments/{id}",
 *     summary="Delete a comment",
 * tags={"User Comments"},
 *     @OA\Parameter(
 *         name="uuid",
 *         in="path",
 *         required=true,
 *         description="Comment UUID",
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Comment successfully deleted",
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Comment not found",
 *     ),
 *   @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *      )
 * )
 */

    public function destroy($id)
    {
        // Find the comment by its ID
        $comment = UserComment::find($id);

        // Delete the comment item from the database
        $comment->delete();

        //Logging the action
        $log['action']='delete_comment';
        $log['description']=$comment->commentText .= ' - comment deleted';
        $log['userId']=auth()->user()->id;
        UserLog::create($log);

        // Redirect the user back to the previous page
        return redirect()->back()->with('message', 'Comment successfully deleted!');
    }

    /**
 * @OA\Get(
 *     path="/comments/{id}/edit",
 *     summary="Get the edit view for a comment",
 * tags={"User Comments"},
 *     @OA\Parameter(
 *         name="uuid",
 *         in="path",
 *         required=true,
 *         description="Comment UUID",
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Edit view for the comment",
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Comment not found",
 *     ),
 *   @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *      )
 * )
 */
    public function edit($id){

        // Find the comment by its ID
        $comment = UserComment::find($id);

        // return the view with resources
        return view('comments.edit', [
            'comment' => $comment
        ]);
    }

    /**
 * @OA\Put(
 *     path="/comments/{id}",
 *     summary="Update a comment",
 * tags={"User Comments"},
 *     @OA\Parameter(
 *         name="uuid",
 *         in="path",
 *         required=true,
 *         description="Comment UUID",
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             required={"commentText"},
 *             @OA\Property(property="commentText", type="string"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=302,
 *         description="Comment successfully updated",
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Comment not found",
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *     ),
 *   @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *      )
 * )
 */

    public function update($id, Request $request){

        // Validate the input
        $formFields=$request->validate([
            'commentText' => 'required'
        ]);

        // Find the comment by its ID
        $comment = UserComment::find($id);

         //Logging the action
         $log['action']='update';
         $log['description']=$comment->commentText .= ' - comment updated';
         $log['userId']=auth()->user()->id;
         UserLog::create($log);

        // Update the comment with $formFields
        $comment->update($formFields);

        // return the view with resources
        return redirect()->back()->with('message', 'Comment successfully updated!');
    }


}
