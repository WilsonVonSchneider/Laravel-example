<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

/**
 * @OA\Tag(
 *     name="Password Reset"
 * )
 */

class NewPasswordController extends Controller
{
     /**
     * Display the password reset view.
     *
     * @OA\Get(
     *     path="/forgot-password/reset",
     *     operationId="newPasswordCreate",
     *     tags={"Password Reset"},
     *     summary="Display the password reset view.",
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="The password reset token.",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="The user's email address.",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset view is displayed.",
     *         @OA\MediaType(
     *             mediaType="text/html",
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Token not found or expired.",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="This password reset token is invalid or has expired."
     *             )
     *         )
     *     ),
     *      @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response="500", description="Internal server error")
     * )
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

     /**
 * @OA\Post(
 *     path="/reset-password",
 *     summary="Reset user's password",
 *     tags={"Password Reset"},
 *     @OA\RequestBody(
 *         description="User's email, password, password confirmation, and reset token",
 *         required=true,
 *         @OA\JsonContent(
 *             required={"token", "email", "password", "password_confirmation"},
 *             @OA\Property(property="token", type="string"),
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="password", type="string", format="password"),
 *             @OA\Property(property="password_confirmation", type="string", format="password")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password successfully reset.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="passwords.reset")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Invalid email or token",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The given data was invalid."),
 *             @OA\Property(property="errors", type="object", example={"email":"The email field is required.","token":"The token field is required."})
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Server error.")
 *         )
 *     )
 * )
 */


    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}
