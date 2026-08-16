<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ImageStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    use ImageStorage;

    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = User::where('is_archived', false);

                return DataTables::of($data)
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
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Server error: '.$e->getMessage(),
                ], 500);
            }
        }

        // rows come from the ajax endpoint above; the page itself needs no data
        return view('pages.user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $supervisors = User::where('is_archived', false)->orderBy('nama')->get(['id', 'nama']);

        return view('pages.user.create', compact('supervisors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validateUserRequest($request);

        $data = $this->handleFileUploads($request, $data);
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);
        $user->syncRoles($request->boolean('is_admin') ? 'admin' : 'employee');

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties(['nama' => $user->nama, 'is_admin' => $user->is_admin])
            ->log('user.created');

        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::with('riwayatJabatan')->findOrFail($id);

        return view('pages.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::with('riwayatJabatan')->findOrFail($id);
        $supervisors = User::where('is_archived', false)
            ->where('id', '!=', $user->id)
            ->orderBy('nama')
            ->get(['id', 'nama']);

        return view('pages.user.edit', compact('user', 'supervisors'));
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
        $user->syncRoles($request->boolean('is_admin') ? 'admin' : 'employee');

        // Log only which fields changed — never credential or identity values.
        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties(['changed' => array_keys($user->getChanges())])
            ->log('user.updated');

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

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties(['nama' => $user->nama])
            ->log('user.archived');

        return redirect()->route('user.index')->with('status', __('Employee archived successfully.'));
    }

    public function archivedUsers(Request $request)
    {
        // Rendered as a client-side DataTable; no ajax branch needed.
        $users = User::where('is_archived', true)->get();

        return view('pages.user.archived', compact('users'));
    }

    public function restore($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_archived' => false]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties(['nama' => $user->nama])
            ->log('user.restored');

        return redirect()->route('user.archived')->with('status', __('Employee restored successfully.'));
    }

    /**
     * Validate user request data.
     */
    private function validateUserRequest(Request $request, $user = null)
    {
        $rules = [
            'id_atasan' => 'nullable|exists:users,id',
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:users,nik'.($user ? ",{$user->id}" : ''),
            'email' => 'required|email|max:255|unique:users,email'.($user ? ",{$user->id}" : ''),
            'npwp' => 'nullable|string|size:16|unique:users,npwp'.($user ? ",{$user->id}" : ''),
            'password' => $user ? 'nullable|min:8' : 'required|min:8',
            'no_telepon' => 'nullable|string|max:15|unique:users,no_telepon'.($user ? ",{$user->id}" : ''),
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
