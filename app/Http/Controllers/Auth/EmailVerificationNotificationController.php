<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Email Verification"
 * )
 *
 * Class EmailVerificationNotificationController
 * @package App\Http\Controllers\Auth
 */

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */

         /**
     * Send a new email verification notification.
     *
     * @OA\Post(
     *      path="/email/verification-notification",
     *      tags={"Email Verification"},
     *      summary="Send a new email verification notification",
     *      description="Send a new email verification notification to the authenticated user.",
     *      @OA\Response(
     *          response=302,
     *          description="Redirects to the previous page with a success message.",
     *          @OA\MediaType(
     *              mediaType="text/html",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden error, user has already verified email.",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="User has already verified email."
     *              )
     *          )
     *
     *      ),
     *  @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Internal server error"),
     *      security={{"bearerAuth": {}}}
     * )
     *
     * @param Request $request
     * @return RedirectResponse
     */

    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
