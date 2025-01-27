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
     *
     * @return \Illuminate\Http\Response
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_bpjs_kesehatan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_bpjs_ketenagakerjaan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

        ]);

        $data = $request->all();

        if ($request->hasFile('foto_profil')) {
            $data['foto_profil'] = $request->file('foto_profil')->store('profile', 'public');
        }

        if ($request->hasFile('foto_ktp')) {
            $data['foto_ktp'] = $request->file('foto_ktp')->store('ktp', 'public');
        }

        if ($request->hasFile('foto_bpjs_kesehatan')) {
            $data['foto_bpjs_kesehatan'] = $request->file('foto_bpjs_kesehatan')->store('bpjs_kesehatan', 'public');
        }

        if ($request->hasFile('foto_bpjs_ketenagakerjaan')) {
            $data['foto_bpjs_ketenagakerjaan'] = $request->file('foto_bpjs_ketenagakerjaan')->store('bpjs_ketenagakerjaan', 'public');
        }

        $data['password'] = Hash::make($request->password);

        $data = $request->validate([
            'id_jabatan' => 'nullable|exists:jabatan,id',
            'id_grup' => 'nullable|exists:grup,id',
            'id_departemen' => 'nullable|exists:departemen,id',
            'id_kantor' => 'nullable|exists:kantor,id',
            'nik' => 'required|string|max:16|unique:users,nik',
            'npwp' => 'required|string|max:16|unique:users,npwp',
            'no_telepon' => 'required|string|max:16|unique:users,no_telepon',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ] + $this->validateUserData($request));

        User::create($data);

        return redirect()->route('user.index');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('pages.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_bpjs_kesehatan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_bpjs_ketenagakerjaan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil) {
                \Storage::disk('public')->delete($user->foto_profil);
            }
            $data['foto_profil'] = $request->file('foto_profil')->store('profile', 'public');
        }

        if ($request->hasFile('foto_ktp')) {
            if ($user->foto_ktp) {
                \Storage::disk('public')->delete($user->foto_ktp);
            }
            $data['foto_ktp'] = $request->file('foto_ktp')->store('ktp', 'public');
        }

        if ($request->hasFile('foto_bpjs_kesehatan')) {
            if ($user->foto_ktp) {
                \Storage::disk('public')->delete($user->foto_ktp);
            }
            $data['foto_bpjs_kesehatan'] = $request->file('foto_bpjs_kesehatan')->store('bpjs_kesehatan', 'public');
        }

        if ($request->hasFile('foto_bpjs_ketenagakerjaan')) {
            if ($user->foto_ktp) {
                \Storage::disk('public')->delete($user->foto_ktp);
            }
            $data['foto_bpjs_ketenagakerjaan'] = $request->file('foto_bpjs_ketenagakerjaan')->store('bpjs_ketenagakerjaan', 'public');
        }

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $data = $request->validate([
            'id_jabatan' => 'nullable|exists:jabatan,id',
            'id_grup' => 'nullable|exists:grup,id',
            'id_departemen' => 'nullable|exists:departemen,id',
            'id_kantor' => 'nullable|exists:kantor,id',
        ] + $this->validateUserData($request));

        $user->update($data);

        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if ($user->photo) {
            $this->deleteImage($user->photo, 'profile');
        }

        $user->delete();

        return redirect()->route('user.index');
    }

    private function validateUserData($request)
    {
        return [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            // Add other fields here as needed
        ];
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
}
