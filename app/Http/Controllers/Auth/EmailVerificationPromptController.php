<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

    /**
     * @OA\Tag(
     *     name="Email Verification",
     *     description="API endpoints for email verification"
     * )
     *
     * @OA\Schema(
     *     schema="EmailVerificationPromptResponse",
     *     type="object",
     *     @OA\Property(property="status", type="string", example="success"),
     *     @OA\Property(property="message", type="string", example="Email verification prompt displayed")
     * )
     */

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View
     *
     * @OA\Get(
     *     path="/email/verify",
     *     tags={"Email Verification"},
     *     summary="Display the email verification prompt.",
     *     description="Displays the email verification prompt if the user has not verified their email address yet.",
     *     operationId="getEmailVerificationPrompt",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/EmailVerificationPromptResponse")
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="User has already verified their email",
     *         @OA\Header(header="Location", description="Redirects to the intended page", @OA\Schema(type="string"))
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Internal server error"),
     *     security={{"bearerAuth":{}}}
     * )
     */

    public function __invoke(Request $request): RedirectResponse|View
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(RouteServiceProvider::HOME)
                    : view('auth.verify-email');
    }
}
