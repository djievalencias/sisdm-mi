<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
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
        });

        if ($response === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Password has been reset successfully.',
            ], Response::HTTP_OK);
        }

        return response()->json([
            'message' => 'Failed to reset password.',
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
