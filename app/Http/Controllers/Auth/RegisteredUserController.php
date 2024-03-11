<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    /**
     * Display the registration view.
     *
     * @OA\Get(
     *     path="/register",
     *     summary="Display the registration form.",
     *     tags={"Authentication"},
     *     @OA\Response(response="200", description="Show the registration form."),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Internal server error"),
     * )
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
   /**
     * Handle an incoming registration request.
     *
     * @OA\Post(
     *     path="/register",
     *     summary="Handle a registration request.",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User registration details.",
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", example="Abcdef1!"),
     *             @OA\Property(property="password_confirmation", type="string", example="Abcdef1!")
     *         ),
     *     ),
     *     @OA\Response(response="302", description="Redirect to the home page."),
     *     @OA\Response(response="422", description="Validation error."),
     *     @OA\Response(response="500", description="Internal server error"),
     * )
     * @throws \Illuminate\Validation\ValidationException
     */

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::min(8)
                                                        ->letters()
                                                        ->mixedCase()
                                                        ->numbers()
                                                        ->symbols()
                                                        ->uncompromised()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'country' => 'us',
            'language' => 'en',
            'category' => 'general',
            'role' => 0
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
