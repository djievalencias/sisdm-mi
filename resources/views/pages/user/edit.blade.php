// TO-DO: Fix update jabatan, grup, departemen, kantor

@extends('layouts.app')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">User</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">User</li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <section class="col-lg-12">

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary mb-2">Back</a>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="ion ion-clipboard mr-1"></i>
                                User
                            </h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('user.update', $user->id) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf @method('PUT')
                                <div class="form-group">
                                    <label for="">Nama</label>
                                    <input type="text" name="nama" class="form-control"
                                        value="{{ old('nama', $user->nama) }}">
                                </div>
                                <div class="form-group">
                                    <label for="">NIK</label>
                                    <input type="text" name="nik" class="form-control"
                                        value="{{ old('nik', $user->nik) }}">
                                </div>
                                <div class="form-group">
                                    <label for="">e-Mail</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', $user->email) }}">
                                </div>
                                <div class="form-group">
                                    <label for="id_kantor">Kantor</label>
                                    <select name="id_kantor" class="form-control">
                                        <option value="">Select Kantor</option>
                                        @foreach ($kantor as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_departemen">Departemen</label>
                                    <select name="id_departemen" class="form-control">
                                        <option value="">Select Departemen</option>
                                        @foreach ($departemen as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_grup">Grup</label>
                                    <select name="id_grup" class="form-control">
                                        <option value="">Select Grup</option>
                                        @foreach ($grup as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_jabatan">Jabatan</label>
                                    <select name="id_jabatan" class="form-control">
                                        <option value="">Select Jabatan</option>
                                        @foreach ($jabatan as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">NPWP</label>
                                    <input type="text" name="npwp" class="form-control"
                                        value="{{ old('npwp', $user->npwp) }}">
                                </div>
                                <div class="form-group">
                                    <label for="">Password</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">No HP</label>
                                    <input type="text" name="no_telepon" class="form-control"
                                        value="{{ old('no_telepon', $user->no_telepon) }}">
                                </div>
                                <div class="form-group">
                                    <label for="">Gender</label>
                                    <select name="jenis_kelamin" class="form-control">
                                        <option value="" {{ old('jenis_kelamin') == '' ? 'selected' : '' }}>Select Gender</option>
                                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Male</option>
                                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control"
                                        value="{{ old('tempat_lahir', $user->tempat_lahir) }}">
                                </div>
                                <div class="form-group">
                                    <label for="">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control"
                                        value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}">
                                </div>
                                <div class="form-group">
                                    <label for="">Agama</label>
                                    <input type="text" name="agama" class="form-control"
                                        value="{{ old('agama', $user->agama) }}">
                                </div>
                                <div class="form-group">
                                    <label for="">Alamat</label>
                                    <textarea name="alamat" class="form-control">{{ old('alamat', $user->alamat) }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">RT</label>
                                    <input type="text" name="rt" class="form-control"
                                        value="{{ old('rt', $user->rt) }}">
                                </div>
                                <div class="form-group">
                                    <label for="">RW</label>
                                    <input type="text" name="rw" class="form-control"
                                        value="{{ old('rw', $user->rw) }}">
                                </div>
                                <div class="form-group">
                                    <label for="">Kelurahan</label>
                                    <input type="text" name="kelurahan" class="form-control"
                                        value="{{ old('kelurahan', $user->kelurahan) }}">
                                </div>
                                <div class="form-group">
                                    <label for="">Kecamatan</label>
                                    <input type="text" name="kecamatan" class="form-control"
                                        value="{{ old('kecamatan', $user->kecamatan) }}">
                                </div>
                                <div class="form-group">
                                    <label for="">Kota</label>
                                    <input type="text" name="kabupaten_kota" class="form-control"
                                        value="{{ old('kabupaten_kota', $user->kabupaten_kota) }}">
                                </div>
                                <div class="form-group">
                                    <label for="">Foto Profil</label>
                                    <input type="file" name="foto_profil" class="form-control-file">
                                    @if ($user->foto_profil)
                                        <img src="{{ asset('/storage/profile/' . $user->foto_profil) }}" alt=""
                                            height="100">
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Foto KTP</label>
                                    <input type="file" name="foto_ktp" class="form-control-file">
                                    @if ($user->foto_ktp)
                                        <img src="{{ asset('/storage/ktp/' . $user->foto_ktp) }}" alt=""
                                            height="100">
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">BPJS Kesehatan</label>
                                    <input type="file" name="foto_bpjs_kesehatan" class="form-control-file">
                                    @if ($user->foto_bpjs_kesehatan)
                                        <img src="{{ asset('/storage/bpjs_kesehatan/' . $user->foto_bpjs_kesehatan) }}"
                                            alt="" height="100">
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">BPJS Ketenagakerjaan</label>
                                    <input type="file" name="foto_bpjs_ketenagakerjaan" class="form-control-file">
                                    @if ($user->foto_bpjs_ketenagakerjaan)
                                        <img src="{{ asset('/storage/bpjs_ketenagakerjaan/' . $user->foto_bpjs_ketenagakerjaan) }}"
                                            alt="" height="100">
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Is Active</label>
                                    <select name="is_aktif" class="form-control">
                                        <option value="1"
                                            {{ old('is_aktif', $user->is_aktif) == 1 ? 'selected' : '' }}>Yes</option>
                                        <option value="0"
                                            {{ old('is_aktif', $user->is_aktif) == 0 ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="" style="display: block">Is Admin</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="is_admin" type="radio" id="inlineRadio1" value="1" {{ old('is_admin', $user->is_admin) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="inlineRadio1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="is_admin" type="radio" id="inlineRadio2" value="0" {{ old('is_admin', $user->is_admin) == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="inlineRadio2">No</label>
                                    </div>
                                </div>
                                

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection
