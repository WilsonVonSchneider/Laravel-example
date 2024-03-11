<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */

     /**
 * @OA\Put(
 *     path="/api/password/update",
 *     summary="Update the user's password",
 *     description="Update the authenticated user's password.",
 *     tags={"Password"},
 *     security={{ "bearerAuth":{} }},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="current_password",
 *                 type="string",
 *                 description="The user's current password."
 *             ),
 *             @OA\Property(
 *                 property="password",
 *                 type="string",
 *                 description="The new password to set for the user."
 *             ),
 *             @OA\Property(
 *                 property="password_confirmation",
 *                 type="string",
 *                 description="Confirmation of the new password."
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Password updated successfully.",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="status",
 *                 type="string",
 *                 description="Status message confirming password update."
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response="422",
 *         description="Validation errors occurred.",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 description="The error message."
 *             ),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 description="Validation errors encountered.",
 *                 @OA\AdditionalProperties(
 *                     type="array",
 *                     @OA\Items(
 *                         type="string",
 *                         description="The specific error message for this field."
 *                     ),
 *                 ),
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response="401",
 *         description="User is not authenticated.",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 description="The error message."
 *             ),
 *         ),
 *     ),
 *
     *  @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Internal server error")
 * )
 */

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
