<?php

namespace App\Http\Controllers;

use App\Traits\ImageStorage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    use ImageStorage;

    public function __construct()
    {
        $this->middleware(['auth', 'is_admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::query();

            return DataTables::eloquent($data)
                ->addColumn('action', function ($data) {
                    return view('layouts._action', [
                        'model' => $data,
                        'edit_url' => route('user.edit', $data->id_user),
                        'show_url' => route('user.show', $data->id_user),
                        'delete_url' => route('user.destroy', $data->id_user),
                    ]);
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.user.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
        $this->validateRequest($request);

        $user = User::create($request->all());

        return response_success($user,'User berhasil ditambahkan.');
        } catch (\Exception $e) {
            return response_error(null, $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            $user = User::find($id);

            return response_success($user, "Successfully get use.");
          } catch (\Exception $e) {
              return response_error(null, $e->getMessage(), $e->getCode());
          }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        try {
            $this->validateRequest($request, $user->id_user);

            $user = User::find($id)->update($request->all());

            return response_success($user,'Berhasil memperbarui user');
        } catch (\Exception $e) {
            return response_error(null, $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user = User::findOrFail($id);
            if ($user->foto_profil) {
                $this->deleteImage($user->foto_profil, 'foto_profil');
            }
            $user->delete();
            return response_success(null, "User berhasil dihapus.");
        } catch (\Exception $e) {
            return response_error(null, $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Prepare data for storing or updating a user.
     */
    private function prepareData(Request $request, User $user = null)
    {
        $data = $request->only([
            'nama', 'email', 'nik', 'npwp', 'no_telepon', 'jenis_kelamin', 
            'tempat_lahir', 'tanggal_lahir', 'tanggal_perekrutan', 
            'agama', 'alamat', 'rt', 'rw', 'kelurahan', 'kecamatan', 'kabupaten_kota',
            'id_jabatan', 'id_atasan', 'is_aktif', 'is_admin'
        ]);

        // Hash password if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle image uploads
        $data['foto_profil'] = $this->uploadImage(
            $request->file('foto_profil'),
            $request->nama,
            'foto_profil',
            $user ? true : false,
            $user->foto_profil ?? null
        );

        $data['foto_ktp'] = $this->uploadImage(
            $request->file('foto_ktp'),
            $request->nama . '_ktp',
            'foto_ktp',
            $user ? true : false,
            $user->foto_ktp ?? null
        );

        $data['foto_bpjs_kesehatan'] = $this->uploadImage(
            $request->file('foto_bpjs_kesehatan'),
            $request->nama . '_bpjs_kesehatan',
            'foto_bpjs_kesehatan',
            $user ? true : false,
            $user->foto_bpjs_kesehatan ?? null
        );

        $data['foto_bpjs_ketenagakerjaan'] = $this->uploadImage(
            $request->file('foto_bpjs_ketenagakerjaan'),
            $request->nama . '_bpjs_ketenagakerjaan',
            'foto_bpjs_ketenagakerjaan',
            $user ? true : false,
            $user->foto_bpjs_ketenagakerjaan ?? null
        );

        return $data;
    }

    /**
     * Validate incoming request data.
     */
    private function validateRequest(Request $request, $id = null)
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email' . ($id ? ",$id" : ''),
            'nik' => 'nullable|digits:16|unique:users,nik' . ($id ? ",$id" : ''),
            'npwp' => 'nullable|digits:16|unique:users,npwp' . ($id ? ",$id" : ''),
            'no_telepon' => 'nullable|numeric|unique:users,no_telepon' . ($id ? ",$id" : ''),
            'jenis_kelamin' => 'nullable|in:L,P',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'tanggal_perekrutan' => 'nullable|date',
            'agama' => 'nullable|string|max:50',
            'alamat' => 'nullable|string|max:255',
            'rt' => 'nullable|string|max:5',
            'rw' => 'nullable|string|max:5',
            'kelurahan' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kabupaten_kota' => 'nullable|string|max:255',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto_bpjs_kesehatan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto_bpjs_ketenagakerjaan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        Validator::make($request->all(), $rules)->validate();
    }
}
