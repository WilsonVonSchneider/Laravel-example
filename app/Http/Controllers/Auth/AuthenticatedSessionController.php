<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * @OA\Tag(
 *     name="Authenticated Session"
 * )
 */

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */

     /**
     * Display the login view.
     *
     * @OA\Get(
     *     path="/login",
     *     operationId="login",
     *     tags={"Authenticated Session"},
     *     summary="Display the login view",
     *     description="Displays the login view for the user to enter their credentials.",
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Internal server error")
     * )
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */

         /**
     * Handle an incoming authentication request.
     *
     * @OA\Post(
     *     path="/login",
     *     operationId="authenticate",
     *     tags={"Authenticated Session"},
     *     summary="Authenticate the user",
     *     description="Authenticates the user and creates a session.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="User credentials",
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password")
     *         )
     *     ),
     *     @OA\Response(response="302", description="Redirect"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Internal server error")
     * )
     *
     * @param LoginRequest $request
     * @return RedirectResponse
     */

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */

     /**
     * Destroy an authenticated session.
     *
     * @OA\Post(
     *     path="/logout",
     *     operationId="logout",
     *     tags={"Authenticated Session"},
     *     summary="Destroy the authenticated session",
     *     description="Destroys the authenticated session and logs the user out.",
     *     @OA\Response(response="302", description="Redirect"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Internal server error")
     * )
     *
     * @param Request $request
     * @return RedirectResponse
     */

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
