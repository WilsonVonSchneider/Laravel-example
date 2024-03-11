<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * @OA\Tag(
 *     name="Confirmable Password",
 * )
 */

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     */

  /**
     * Show the confirm password view.
     *
     * @OA\Get(
     *      path="/confirm-password",
     *      operationId="confirmPasswordView",
     *      tags={"Confirmable Password"},
     *      summary="Show the confirm password view",
     *      description="Returns the confirm password view",
     *      @OA\Response(
     *          response=200,
     *          description="Success"),
     *      @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Internal server error")
     * )
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Confirm the user's password.
     */


    /**
     * Confirm the user's password.
     *
     * @OA\Post(
     *      path="/confirm-password",
     *      operationId="confirmPassword",
     *      tags={"Confirmable Password"},
     *      summary="Confirm the user's password",
     *      description="Confirms the user's password",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Pass user's email and password",
     *          @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *              @OA\Property(property="password", type="string", example="password")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="The password has been confirmed.")
     *          )
     *      ),
     *      @OA\Response(response=401, description="Unauthenticated"),
     *      @OA\Response(response=422, description="Invalid input"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Internal server error")
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function store(Request $request): RedirectResponse
    {
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
