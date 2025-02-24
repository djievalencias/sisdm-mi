<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:255', 'unique:users,nik'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'npwp' => ['required', 'string', 'max:255', 'unique:users,npwp'],
            'password' => ['required', 'string', 'min:8'],
            'no_telepon' => ['required', 'string', 'min:8'],
            'device_name' => ['required', 'string']
        ]);
        

        $data['password'] = Hash::make($request->password);
        $data['is_admin'] = $request->is_admin ?? false;

        $user = User::create($data);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Email/password salah.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $accessToken = Auth::user()->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'message' => 'success',
            'data' => $user,
            'meta' => [
                'token' => $accessToken
            ]
        ], Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->select(['id', 'email', 'password'])->first();

        if(!$user)
        {
            throw ValidationException::withMessages([
                'message' => ['Email tidak terdaftar.'],
            ]);
        }

        if (empty($user) || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'message' => ['Email/password salah.'],
            ]);
        }

        $accessToken = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'message' => 'success',
            'data' => $user,
            'meta' => [
                'token' => $accessToken
            ]
        ], Response::HTTP_CREATED);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Log out berhasil.'
        ], Response::HTTP_OK);
    }
}
