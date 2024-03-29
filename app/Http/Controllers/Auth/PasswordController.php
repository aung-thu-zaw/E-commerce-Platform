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
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        if ($request->user()) {
            $request->user()->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        return to_route('my-account.edit', 'tab=change-password')->with('success', 'PASSWORD_UPDATED_SUCCESSFULLY');
    }
}
