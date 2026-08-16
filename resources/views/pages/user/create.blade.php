@extends('layouts.app')

@section('content')
    <x-page :title="__('Add Employee')" :breadcrumb="__('Employees')">
        <form action="{{ route('user.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Account') }}</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="nama">{{ __('Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama') }}" required maxlength="255">
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="email">{{ __('E-mail') }} <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password">{{ __('Password') }} <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                                required minlength="8">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="id_atasan">{{ __('Supervisor') }}</label>
                            <select name="id_atasan" id="id_atasan" class="form-control @error('id_atasan') is-invalid @enderror">
                                <option value="">{{ __('No supervisor') }}</option>
                                @foreach ($supervisors as $supervisor)
                                    <option value="{{ $supervisor->id }}" {{ old('id_atasan') == $supervisor->id ? 'selected' : '' }}>{{ $supervisor->nama }}</option>
                                @endforeach
                            </select>
                            @error('id_atasan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="is_aktif">{{ __('Active') }}</label>
                            <select name="is_aktif" id="is_aktif" class="form-control">
                                <option value="1" {{ old('is_aktif', 1) == 1 ? 'selected' : '' }}>{{ __('Yes') }}</option>
                                <option value="0" {{ old('is_aktif') === '0' ? 'selected' : '' }}>{{ __('No') }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label style="display: block">{{ __('Administrator Access') }}</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="is_admin" type="radio" id="inlineRadio1" value="1" {{ old('is_admin') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="inlineRadio1">{{ __('Yes') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="is_admin" type="radio" id="inlineRadio2" value="0" {{ old('is_admin', 0) == 0 ? 'checked' : '' }}>
                                <label class="form-check-label" for="inlineRadio2">{{ __('No') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Personal Information') }}</h3>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nik">{{ __('NIK') }} <span class="text-danger">*</span></label>
                            <input type="text" name="nik" id="nik" class="form-control @error('nik') is-invalid @enderror"
                                value="{{ old('nik') }}" required minlength="16" maxlength="16">
                            @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="npwp">{{ __('NPWP') }}</label>
                            <input type="text" name="npwp" id="npwp" class="form-control @error('npwp') is-invalid @enderror" value="{{ old('npwp') }}">
                            @error('npwp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="no_telepon">{{ __('Phone Number') }}</label>
                            <input type="text" name="no_telepon" id="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror" value="{{ old('no_telepon') }}">
                            @error('no_telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="jenis_kelamin">{{ __('Gender') }} <span class="text-danger">*</span></label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror" required>
                                <option value="" {{ old('jenis_kelamin') == '' ? 'selected' : '' }}>{{ __('Select Gender') }}</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>{{ __('Male') }}</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>{{ __('Female') }}</option>
                            </select>
                            @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="tempat_lahir">{{ __('Place of Birth') }}</label>
                            <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{ old('tempat_lahir') }}">
                            @error('tempat_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tanggal_lahir">{{ __('Date of Birth') }}</label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir') }}">
                            @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="agama">{{ __('Religion') }}</label>
                            <input type="text" name="agama" id="agama" class="form-control @error('agama') is-invalid @enderror" value="{{ old('agama') }}">
                            @error('agama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="pendidikan">{{ __('Education') }}</label>
                            <input type="text" name="pendidikan" id="pendidikan" class="form-control @error('pendidikan') is-invalid @enderror" value="{{ old('pendidikan') }}">
                            @error('pendidikan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="status_perkawinan">{{ __('Marital Status') }}</label>
                            <select name="status_perkawinan" id="status_perkawinan" class="form-control">
                                <option value="Belum menikah" {{ old('status_perkawinan') == 'Belum menikah' ? 'selected' : '' }}>{{ __('Single') }}</option>
                                <option value="Menikah" {{ old('status_perkawinan') == 'Menikah' ? 'selected' : '' }}>{{ __('Married') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Address') }}</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="alamat">{{ __('Address') }}</label>
                        <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat') }}</textarea>
                        @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="rt">{{ __('RT') }}</label>
                            <input type="text" name="rt" id="rt" class="form-control" value="{{ old('rt') }}">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="rw">{{ __('RW') }}</label>
                            <input type="text" name="rw" id="rw" class="form-control" value="{{ old('rw') }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="kelurahan">{{ __('Village') }}</label>
                            <input type="text" name="kelurahan" id="kelurahan" class="form-control" value="{{ old('kelurahan') }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="kecamatan">{{ __('District') }}</label>
                            <input type="text" name="kecamatan" id="kecamatan" class="form-control" value="{{ old('kecamatan') }}">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="kabupaten_kota">{{ __('City/Regency') }}</label>
                            <input type="text" name="kabupaten_kota" id="kabupaten_kota" class="form-control" value="{{ old('kabupaten_kota') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Documents') }}</h3>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="foto_profil">{{ __('Profile Photo') }}</label>
                            <input type="file" name="foto_profil" id="foto_profil" accept="image/*" class="form-control-file @error('foto_profil') is-invalid @enderror">
                            @error('foto_profil')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="foto_ktp">{{ __('ID Card Photo') }}</label>
                            <input type="file" name="foto_ktp" id="foto_ktp" accept="image/*" class="form-control-file @error('foto_ktp') is-invalid @enderror">
                            @error('foto_ktp')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="foto_bpjs_kesehatan">{{ __('BPJS Health Card') }}</label>
                            <input type="file" name="foto_bpjs_kesehatan" id="foto_bpjs_kesehatan" accept="image/*" class="form-control-file @error('foto_bpjs_kesehatan') is-invalid @enderror">
                            @error('foto_bpjs_kesehatan')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="foto_bpjs_ketenagakerjaan">{{ __('BPJS Employment Card') }}</label>
                            <input type="file" name="foto_bpjs_ketenagakerjaan" id="foto_bpjs_ketenagakerjaan" accept="image/*" class="form-control-file @error('foto_bpjs_ketenagakerjaan') is-invalid @enderror">
                            @error('foto_bpjs_ketenagakerjaan')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    <a href="{{ route('user.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                </div>
            </div>
        </form>
    </x-page>
@endsection
