@extends('layouts.app')

@section('content')
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
                    <li class="breadcrumb-item active">Add</li>
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

                <a href="{{ route('user.index') }}" class="btn btn-sm btn-primary mb-2">Back</a>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ion ion-clipboard mr-1"></i>
                            User
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="">Nama</label>
                                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}">
                            </div>
                            <div class="form-group">
                                <label for="">NIK</label>
                                <input type="text" name="nik" class="form-control" value="{{ old('nik') }}">
                            </div>
                            <div class="form-group">
                                <label for="">e-Mail</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                            </div>
                            <div class="form-group">
                                <label for="">NPWP</label>
                                <input type="text" name="npwp" class="form-control" value="{{ old('npwp') }}">
                            </div>
                            <div class="form-group">
                                <label for="">Password</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">No HP</label>
                                <input type="text" name="no_telepon" class="form-control" value="{{ old('no_telepon') }}">
                            </div>
                            <div class="form-group">
                                <label for="">Gender</label>
                                <select name="jenis_kelamin" class="form-control">
                                    <option value="" {{ old('jenis_kelamin') == '' ? 'selected' : '' }}>Select Gender</option>
                                    <option value="M" {{ old('jenis_kelamin') == 'M' ? 'selected' : '' }}>Male</option>
                                    <option value="F" {{ old('jenis_kelamin') == 'F' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}">
                            </div>
                            <div class="form-group">
                                <label for="">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}">
                            </div>
                            <div class="form-group">
                                <label for="">Agama</label>
                                <input type="text" name="agama" class="form-control" value="{{ old('agama') }}">
                            </div>
                            <div class="form-group">
                                <label for="">Alamat</label>
                                <textarea name="alamat" class="form-control">{{ old('alamat') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="">RT</label>
                                <input type="text" name="rt" class="form-control" value="{{ old('rt') }}">
                            </div>
                            <div class="form-group">
                                <label for="">RW</label>
                                <input type="text" name="rw" class="form-control" value="{{ old('rw') }}">
                            </div>
                            <div class="form-group">
                                <label for="">Kelurahan</label>
                                <input type="text" name="kelurahan" class="form-control" value="{{ old('kelurahan') }}">
                            </div>
                            <div class="form-group">
                                <label for="">Kecamatan</label>
                                <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan') }}">
                            </div>
                            <div class="form-group">
                                <label for="">Kabupaten/Kota</label>
                                <input type="text" name="kabupaten_kota" class="form-control" value="{{ old('kabupaten_kota') }}">
                            </div>
                            <div class="form-group">
                                <label for="">Foto Profil</label>
                                <input type="file" name="foto_profil" class="form-control-file">
                            </div>
                            <div class="form-group">
                                <label for="">Foto KTP</label>
                                <input type="file" name="foto_ktp" class="form-control-file">
                            </div>
                            <div class="form-group">
                                <label for="">BPJS Kesehatan</label>
                                <input type="file" name="foto_bpjs_kesehatan" class="form-control-file">
                            </div>
                            <div class="form-group">
                                <label for="">BPJS Ketenagakerjaan</label>
                                <input type="file" name="foto_bpjs_ketenagakerjaan" class="form-control-file">
                            </div>
                            <div class="form-group">
                                <label for="">Is Active</label>
                                <select name="is_aktif" class="form-control">
                                    <option value="1" {{ old('is_aktif') == 1 ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('is_aktif') == 0 ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="" style="display: block">Is Admin</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" name="is_admin" type="radio" id="inlineRadio1" value="1" {{ old('is_admin') == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inlineRadio1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" name="is_admin" type="radio" id="inlineRadio2" value="0" {{ old('is_admin') == 0 ? 'checked' : '' }}>
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
