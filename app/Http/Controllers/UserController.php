<?php

namespace App\Http\Controllers;

use App\Traits\ImageStorage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
                        'edit_url' => route('user.edit', $data->id),
                        'show_url' => route('user.show', $data->id),
                        'delete_url' => route('user.destroy', $data->id),
                    ]);
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.user.create');
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
        return view('pages.user.edit', compact('user'));
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
}
