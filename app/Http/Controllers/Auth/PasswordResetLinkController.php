<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

/**
 * @OA\Tag(
 *     name="Password Reset",
 * )
 *
 * Class PasswordResetLinkController
 */

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */

     /**
     * Display the password reset link request view.
     *
     * @return \Illuminate\Contracts\View\View
     *
     * @OA\Get(
     *     path="/forgot-password",
     *     summary="Display the password reset link request view",
     *     tags={"Password Reset"},
     *     @OA\Response(
     *         response="200",
     *         description="Returns the password reset link request view",
     *         @OA\MediaType(
     *             mediaType="text/html"
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Internal server error"),
     * )
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

     /**
     * Handle an incoming password reset link request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @OA\Post(
     *     path="/forgot-password",
     *     summary="Handle an incoming password reset link request",
     *     tags={"Password Reset"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass email address for which password reset link is to be sent",
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", example="johndoe@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response="302",
     *         description="Returns redirect response with success message",
     *         @OA\MediaType(
     *             mediaType="text/html"
     *         )
     *     )
     * )
     */

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}
