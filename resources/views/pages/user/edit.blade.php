@extends('layouts.app')

@section('content')
    <x-page :title="__('Edit Employee')" :breadcrumb="__('Employees')">
        <form action="{{ route('user.update', $user->id) }}" method="post" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Account') }}</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="nama">{{ __('Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama', $user->nama) }}" required maxlength="255">
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="email">{{ __('E-mail') }} <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password">{{ __('Password') }}</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" minlength="8">
                            <small class="form-text text-muted">{{ __('Leave blank to keep the current password.') }}</small>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="id_atasan">{{ __('Supervisor') }}</label>
                            <select name="id_atasan" id="id_atasan" class="form-control @error('id_atasan') is-invalid @enderror">
                                <option value="">{{ __('No supervisor') }}</option>
                                @foreach ($supervisors as $supervisor)
                                    <option value="{{ $supervisor->id }}" {{ old('id_atasan', $user->id_atasan) == $supervisor->id ? 'selected' : '' }}>{{ $supervisor->nama }}</option>
                                @endforeach
                            </select>
                            @error('id_atasan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="is_aktif">{{ __('Active') }}</label>
                            <select name="is_aktif" id="is_aktif" class="form-control">
                                <option value="1" {{ old('is_aktif', $user->is_aktif) == 1 ? 'selected' : '' }}>{{ __('Yes') }}</option>
                                <option value="0" {{ old('is_aktif', $user->is_aktif) == 0 ? 'selected' : '' }}>{{ __('No') }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label style="display: block">{{ __('Administrator Access') }}</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="is_admin" type="radio" id="inlineRadio1" value="1" {{ old('is_admin', $user->is_admin) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="inlineRadio1">{{ __('Yes') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="is_admin" type="radio" id="inlineRadio2" value="0" {{ old('is_admin', $user->is_admin) == 0 ? 'checked' : '' }}>
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
                                value="{{ old('nik', $user->nik) }}" required minlength="16" maxlength="16">
                            @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="npwp">{{ __('NPWP') }}</label>
                            <input type="text" name="npwp" id="npwp" class="form-control @error('npwp') is-invalid @enderror"
                                value="{{ old('npwp', $user->npwp) }}">
                            @error('npwp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="no_telepon">{{ __('Phone Number') }}</label>
                            <input type="text" name="no_telepon" id="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror"
                                value="{{ old('no_telepon', $user->no_telepon) }}">
                            @error('no_telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="jenis_kelamin">{{ __('Gender') }}</label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror">
                                <option value="" {{ old('jenis_kelamin', $user->jenis_kelamin ?? '') == '' ? 'selected' : '' }}>{{ __('Select Gender') }}</option>
                                <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>{{ __('Male') }}</option>
                                <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>{{ __('Female') }}</option>
                            </select>
                            @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="tempat_lahir">{{ __('Place of Birth') }}</label>
                            <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror"
                                value="{{ old('tempat_lahir', $user->tempat_lahir) }}">
                            @error('tempat_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tanggal_lahir">{{ __('Date of Birth') }}</label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                value="{{ old('tanggal_lahir', $user->tanggal_lahir?->format('Y-m-d')) }}">
                            @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="agama">{{ __('Religion') }}</label>
                            <input type="text" name="agama" id="agama" class="form-control @error('agama') is-invalid @enderror"
                                value="{{ old('agama', $user->agama) }}">
                            @error('agama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="pendidikan">{{ __('Education') }}</label>
                            <input type="text" name="pendidikan" id="pendidikan" class="form-control @error('pendidikan') is-invalid @enderror"
                                value="{{ old('pendidikan', $user->pendidikan) }}">
                            @error('pendidikan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="status_perkawinan">{{ __('Marital Status') }}</label>
                            <select name="status_perkawinan" id="status_perkawinan" class="form-control">
                                <option value="Belum menikah" {{ old('status_perkawinan', $user->status_perkawinan) == 'Belum menikah' ? 'selected' : '' }}>{{ __('Single') }}</option>
                                <option value="Menikah" {{ old('status_perkawinan', $user->status_perkawinan) == 'Menikah' ? 'selected' : '' }}>{{ __('Married') }}</option>
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
                        <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $user->alamat) }}</textarea>
                        @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="rt">{{ __('RT') }}</label>
                            <input type="text" name="rt" id="rt" class="form-control" value="{{ old('rt', $user->rt) }}">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="rw">{{ __('RW') }}</label>
                            <input type="text" name="rw" id="rw" class="form-control" value="{{ old('rw', $user->rw) }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="kelurahan">{{ __('Village') }}</label>
                            <input type="text" name="kelurahan" id="kelurahan" class="form-control" value="{{ old('kelurahan', $user->kelurahan) }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="kecamatan">{{ __('District') }}</label>
                            <input type="text" name="kecamatan" id="kecamatan" class="form-control" value="{{ old('kecamatan', $user->kecamatan) }}">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="kabupaten_kota">{{ __('City/Regency') }}</label>
                            <input type="text" name="kabupaten_kota" id="kabupaten_kota" class="form-control" value="{{ old('kabupaten_kota', $user->kabupaten_kota) }}">
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
                        @foreach ([
                            'foto_profil' => __('Profile Photo'),
                            'foto_ktp' => __('ID Card Photo'),
                            'foto_bpjs_kesehatan' => __('BPJS Health Card'),
                            'foto_bpjs_ketenagakerjaan' => __('BPJS Employment Card'),
                        ] as $field => $label)
                            <div class="form-group col-md-6">
                                <label for="{{ $field }}">{{ $label }}</label>
                                <input type="file" name="{{ $field }}" id="{{ $field }}" accept="image/*"
                                    class="form-control-file @error($field) is-invalid @enderror">
                                @error($field)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                @if ($user->$field)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $user->$field) }}" alt="{{ $label }}" width="100" class="img-thumbnail">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    <a href="{{ route('user.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                </div>
            </div>
        </form>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Job History') }}</h3>
                <a href="{{ route('riwayat_jabatan.create', $user->id) }}" class="btn btn-sm btn-success float-right">{{ __('Create') }}</a>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('Office') }}</th>
                            <th>{{ __('Department') }}</th>
                            <th>{{ __('Group') }}</th>
                            <th>{{ __('Position') }}</th>
                            <th>{{ __('Start Date') }}</th>
                            <th>{{ __('End Date') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($user->riwayatJabatan && $user->riwayatJabatan->count())
                            @foreach ($user->riwayatJabatan as $riwayatJabatan)
                                <tr>
                                    <td>{{ $riwayatJabatan->jabatan->grup->departemen->kantor->nama ?? '-' }}</td>
                                    <td>{{ $riwayatJabatan->jabatan->grup->departemen->nama ?? '-' }}</td>
                                    <td>{{ $riwayatJabatan->jabatan->grup->nama ?? '-' }}</td>
                                    <td>{{ $riwayatJabatan->jabatan->nama ?? '-' }}</td>
                                    <td>{{ $riwayatJabatan->tanggal_mulai }}</td>
                                    <td>{{ $riwayatJabatan->tanggal_selesai ?? __('Present') }}</td>
                                    <td>
                                        <a href="{{ route('riwayat_jabatan.edit', [$user->id, $riwayatJabatan->id]) }}" class="btn btn-sm btn-warning">{{ __('Edit') }}</a>
                                        <form action="{{ route('riwayat_jabatan.destroy', [$user->id, $riwayatJabatan->id]) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('Are you sure you want to delete this data?') }}')">{{ __('Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center"><strong>{{ __('No job history yet') }}</strong></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </x-page>
@endsection
