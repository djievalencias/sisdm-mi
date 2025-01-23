<?php

namespace App\Http\Controllers;

use App\Traits\ImageStorage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Jabatan;
use App\Models\Grup;
use App\Models\Departemen;
use App\Models\Kantor;

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
            $data = User::where('is_archived', false)->with(['jabatan', 'jabatan.grup', 'jabatan.grup.departemen', 'jabatan.grup.departemen.kantor'])->get();

            return DataTables::eloquent($data)
                ->addColumn('action', function ($data) {
                    return view('layouts._action', [
                        'model' => $data,
                        'edit_url' => route('user.edit', $data->id),
                        'show_url' => route('user.show', $data->id),
                        'archive_url' => route('user.archive', $data->id),
                        'delete_url' => route('user.destroy', $data->id),
                    ]);
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->toJson();
        }

        $users = User::where('is_archived', false)->with(['jabatan', 'jabatan.grup', 'jabatan.grup.departemen', 'jabatan.grup.departemen.kantor'])->get();

        return view('pages.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jabatan = Jabatan::all();
        $grup = Grup::all();
        $departemen = Departemen::all();
        $kantor = Kantor::all();

        return view('pages.user.create', compact('jabatan', 'grup', 'departemen', 'kantor'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validateUserRequest($request);

        $data = $this->handleFileUploads($request, $data);
        $data['password'] = Hash::make($request->password);

        User::create($data);

        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('pages.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $jabatan = Jabatan::all();
        $grup = Grup::all();
        $departemen = Departemen::all();
        $kantor = Kantor::all();

        return view('pages.user.edit', compact('user', 'jabatan', 'grup', 'departemen', 'kantor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $this->validateUserRequest($request, $user);
        $data = $this->handleFileUploads($request, $data, $user);

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if ($user->foto_profil) {
            $this->deleteImage($user->foto_profil, 'profile');
        }

        $user->delete();

        return redirect()->route('user.index');
    }

    public function archive($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_archived' => true]);
        return redirect()->route('user.index')->with('status', 'User archived successfully!');
    }

    public function archivedUsers(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('is_archived', true)->get();

            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    return view('layouts._action', [
                        'model' => $data,
                        'restore_url' => route('user.restore', $data->id),
                        'delete_url' => route('user.destroy', $data->id),
                    ]);
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->toJson();
        }

        $users = User::where('is_archived', true)->get();
        return view('pages.user.archived', compact('users'));
    }

    public function restore($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_archived' => false]);
        return redirect()->route('user.archived')->with('status', 'User restored successfully!');
    }

    /**
     * Validate user request data.
     */
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


    /**
     * Handle file uploads for the user.
     */
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
