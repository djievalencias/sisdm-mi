<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\PasswordChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpFoundation\Response;

class PasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $response = Password::sendResetLink($request->only('email'));

        if ($response === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Reset password link sent.',
            ], Response::HTTP_OK);
        }

        return response()->json([
            'message' => 'Failed to send reset password link.',
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    // Serves both the mobile API and the web reset form (routes/web.php
    // password.update), so the response is content-negotiated.
    public function reset(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'token' => ['required'],
        ]);

        $response = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();

            if ($user->email) {
                $user->notify(new PasswordChanged);
            }
        });

        if ($response === Password::PASSWORD_RESET) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Password has been reset successfully.',
                ], Response::HTTP_OK);
            }

            return redirect()->route('login')->with('password_changed', true);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Failed to reset password.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // trans() resolves the broker status (invalid token, expired, ...).
        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans($response)]);
    }
}
