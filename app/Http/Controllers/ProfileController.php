<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\View\View;
use App\Models\TopHeadline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;


class ProfileController extends Controller
{
    /**
        * Display the user's profile form.
    */

    /**
    * @OA\Get(
    *     path="/profile",
    *     summary="Get edit profile view",
    *     description="Retrieve the edit profile view with data required for the form.",
    *     tags={"Profile"},
    *     security={ {"bearerAuth": {} } },
    *     @OA\Response(
    *         response=200,
    *         description="Success",
    *         @OA\JsonContent(
    *             @OA\Property(
    *                 property="user",
    *                 ref="#/components/schemas/User"
    *             ),
    *             @OA\Property(
    *                 property="allLanguageNames",
    *                 type="object",
    *                 additionalProperties=@OA\Property(
    *                     type="string"
    *                 )
    *             ),
    *             @OA\Property(
    *                 property="allCountryNames",
    *                 type="object",
    *                 additionalProperties=@OA\Property(
    *                     type="string"
    *                 )
    *             ),
    *             @OA\Property(
    *                 property="categories",
    *                 type="array",
    *                 @OA\Items(
    *                     type="string"
    *                 )
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Unauthorized"
    *     ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal server error"
    *      )
    * )
    */
    public function edit(Request $request): View
    {
        $countryNames = json_decode(config('global.isoCountries'), true);
        $languageNames = json_decode(config('global.isoLangs'), true);
        $countriesAll = TopHeadline::getCountries();
        $languagesAll = News::getLanguages();
        $categories = TopHeadline::getCategories();

        for ($x = 0; $x < count($countriesAll); $x++) {
            $allCountryNames[$countriesAll[$x]] = $countryNames[strtoupper($countriesAll[$x])];
        }

        for ($y = 0; $y < count($languagesAll); $y++) {
            $allLanguageNames[$languagesAll[$y]] = $languageNames[($languagesAll[$y])];
        }

        return view('profile.edit', [
            'user' => $request->user(),
            'allLanguageNames' => $allLanguageNames,
            'allCountryNames' => $allCountryNames,
            'categories' => $categories

        ]);
    }

    /**
        * Update the user's profile information.
    */

    /**
    * @OA\Put(
    *      path="/profile",
    *      operationId="updateUserProfile",
    *      tags={"Profile"},
    *      summary="Update the user's profile information",
    *      description="Updates the authenticated user's profile information.",
    *      security={{"bearerAuth":{}}},
    *      @OA\RequestBody(
    *          required=true,
    *          description="User's profile information to be updated",
    * @OA\JsonContent(
    *             @OA\Property(
    *                 property="name",
    *                 type="string"
    *             ),
    * @OA\Property(
    *                 property="email",
    *                 type="string"
    *             ),
    *             @OA\Property(
    *                 property="language",
    *                 type="string"
    *             ),
    *             @OA\Property(
    *                 property="country",
    *                 type="string"
    *             ),
    *             @OA\Property(
    *                 property="categories",
    *                 type="string"
    *             ),
    *             @OA\Property(
    *                 property="current_password",
    *                 type="string"
    *             ),
    *             @OA\Property(
    *                 property="new_password",
    *                 type="string"
    *             ),
    *             @OA\Property(
    *                 property="confirm_password",
    *                 type="string"
    *             )
    *          )
    *      ),
    *      @OA\Response(
    *          response=302,
    *          description="Redirects to the profile edit page with a success message.",
    *          @OA\JsonContent(
    *              @OA\Property(property="status", type="string", example="profile-updated")
    *          )
    *      ),
    *      @OA\Response(
    *          response=401,
    *          description="Unauthorized action, user is not authenticated.",
    *          @OA\JsonContent(
    *              @OA\Property(property="message", type="string", example="Unauthenticated.")
    *          )
    *      ),
    *      @OA\Response(
    *          response=422,
    *          description="Validation errors in the request.",
    *
    *      ),
    *      @OA\Response(
    *          response=500,
    *          description="Internal server error"
    *      )
    * )
    */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
        * Delete the user's account.
    */

     /**
    * @OA\Delete(
    *     path="/profile",
    *     summary="Delete the user's account.",
    *     description="Delete the user's account. Requires current user's password.",
    *     tags={"Profile"},
    *     security={{"bearerAuth": {}}},
    *     @OA\RequestBody(
    *         @OA\MediaType(
    *             mediaType="application/json",
    *             @OA\Schema(
    *                 @OA\Property(
    *                     property="password",
    *                     type="string",
    *                     description="The current user's password.",
    *                 ),
    *                 required={"password"}
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response="200",
    *         description="User account has been deleted successfully.",
    *         @OA\JsonContent(
    *             @OA\Property(
    *                 property="message",
    *                 type="string",
    *                 example="User account has been deleted successfully."
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response="401",
    *         description="Unauthorized access",
    *         @OA\JsonContent(
    *             @OA\Property(
    *                 property="message",
    *                 type="string",
    *                 example="Unauthorized access"
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response="422",
    *         description="Validation errors",
    *         @OA\JsonContent(
    *             @OA\Property(
    *                 property="message",
    *                 type="string",
    *                 example="The given data was invalid."
    *             ),
    *             @OA\Property(
    *                 property="errors",
    *                 type="object",
    *                 example={
    *                     "password": {
    *                         "The current password is invalid."
    *                     }
    *                 }
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response="500",
    *         description="Server error",
    *         @OA\JsonContent(
    *             @OA\Property(
    *                 property="message",
    *                 type="string",
    *                 example="Server error occurred while deleting the user's account."
    *             )
    *         )
    *     )
    * )
    */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
