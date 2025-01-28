<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('is_archived', false)->get();
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::where('is_archived', false)->find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found or is archived'
            ], 404);
        }
    
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        try {
            // Fetch the user, ensuring it's not archived
            $user = User::where('is_archived', false)->find($id);

            if (!$user) {
                return response()->json([
                    'message' => 'User not found or is archived'
                ], 404);
            }

            // Validate request
            $data = $this->validateUserRequest($request, $user);

            // Handle file uploads
            $data = $this->handleFileUploads($request, $data, $user);

            // Hash password if provided
            if ($request->password) {
                $data['password'] = Hash::make($request->password);
            }

            // Update the user
            $user->update($data);

            return response()->json($user, 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Handle other exceptions
            Log::error('Update Error:', ['message' => $e->getMessage()]);
            return response()->json([
                'message' => 'An error occurred during update',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // public function destroy($id)
    // {
    //     $user = User::find($id);

    //     if (!$user) {
    //         return response()->json([
    //             'message' => 'User not found'
    //         ], 404);
    //     }

    //     if ($user->photo) {
    //         $this->deleteImage($user->photo, 'profile');
    //     }
        
    //     $user->delete();

    //     return response()->json(['message' => 'User deleted']);
    // }

    private function validateUserRequest(Request $request, $user = null)
    {
        $rules = [
            'id_jabatan' => 'nullable|exists:jabatan,id',
            'id_atasan' => 'nullable|exists:users,id',
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:users,nik' . ($user ? ",{$user->id}" : ''),
            'email' => 'required|email|max:255|unique:users,email' . ($user ? ",{$user->id}" : ''),
            'npwp' => 'nullable|string|size:16|unique:users,npwp' . ($user ? ",{$user->id}" : ''),
            'password' => $user ? 'nullable|min:8' : 'required|min:8',
            'no_telepon' => 'nullable|string|max:15|unique:users,no_telepon' . ($user ? ",{$user->id}" : ''),
            'jenis_kelamin' => 'required|in:P,L',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'tanggal_perekrutan' => 'nullable|date',
            'tanggal_pemutusan_kontrak' => 'nullable|date|after_or_equal:tanggal_perekrutan',
            'agama' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'kelurahan' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kabupaten_kota' => 'nullable|string|max:255',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_bpjs_kesehatan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_bpjs_ketenagakerjaan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_aktif' => 'nullable|boolean',
            'is_admin' => 'nullable|boolean',
            'is_archived' => 'nullable|boolean',
            'is_remote' => 'nullable|boolean',
            'email_verified_at' => 'nullable|date',
        ];

        return $request->validate($rules);
    }

    private function handleFileUploads(Request $request, array $data, $user = null)
    {
        $files = [
            'foto_profil' => 'profile',
            'foto_ktp' => 'ktp',
            'foto_bpjs_kesehatan' => 'bpjs_kesehatan',
            'foto_bpjs_ketenagakerjaan' => 'bpjs_ketenagakerjaan',
        ];

        foreach ($files as $field => $path) {
            if ($request->hasFile($field)) {
                if ($user && $user->$field) {
                    \Storage::disk('public')->delete($user->$field);
                }
                $data[$field] = $request->file($field)->store($path, 'public');
            }
        }

        return $data;
    }
}
