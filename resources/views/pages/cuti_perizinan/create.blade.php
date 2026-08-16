@extends('layouts.app')

@section('content')
    <x-page :title="__('Submit Leave Request')" :breadcrumb="__('Leave Requests')">
        <div class="card">
            <form action="{{ route('cuti-perizinan.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="id_user">{{ __('Employee') }} <span class="text-danger">*</span></label>
                        <select name="id_user" id="id_user" class="form-control @error('id_user') is-invalid @enderror" required>
                            <option value="">{{ __('Select employee') }}</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ old('id_user') == $user->id ? 'selected' : '' }}>{{ $user->nama }}</option>
                            @endforeach
                        </select>
                        @error('id_user')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="tanggal_mulai">{{ __('Start Date') }} <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                value="{{ old('tanggal_mulai') }}" required>
                            @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tanggal_selesai">{{ __('End Date') }} <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                value="{{ old('tanggal_selesai') }}" required>
                            @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">{{ __('Notes') }} <span class="text-danger">*</span></label>
                        <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" required>{{ old('keterangan') }}</textarea>
                        @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="jenis">{{ __('Type') }} <span class="text-danger">*</span></label>
                            <select name="jenis" id="jenis" class="form-control @error('jenis') is-invalid @enderror" required>
                                <option value="izin" {{ old('jenis') == 'izin' ? 'selected' : '' }}>{{ __('Permission') }}</option>
                                <option value="alpa" {{ old('jenis') == 'alpa' ? 'selected' : '' }}>{{ __('Absence') }}</option>
                                <option value="sakit" {{ old('jenis') == 'sakit' ? 'selected' : '' }}>{{ __('Sick') }}</option>
                            </select>
                            @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="status_pengajuan">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select name="status_pengajuan" id="status_pengajuan" class="form-control @error('status_pengajuan') is-invalid @enderror" required>
                                <option value="diajukan" {{ old('status_pengajuan') == 'diajukan' ? 'selected' : '' }}>{{ __('Submitted') }}</option>
                                <option value="disetujui" {{ old('status_pengajuan') == 'disetujui' ? 'selected' : '' }}>{{ __('Approved') }}</option>
                                <option value="ditolak" {{ old('status_pengajuan') == 'ditolak' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                            </select>
                            @error('status_pengajuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="disetujui_oleh">{{ __('Approved By') }}</label>
                        <select name="disetujui_oleh" id="disetujui_oleh" class="form-control @error('disetujui_oleh') is-invalid @enderror">
                            <option value="">{{ __('Select employee') }}</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ old('disetujui_oleh') == $user->id ? 'selected' : '' }}>{{ $user->nama }}</option>
                            @endforeach
                        </select>
                        @error('disetujui_oleh')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="surat_izin">{{ __('Supporting Document') }}</label>
                        <input type="text" name="surat_izin" id="surat_izin" class="form-control @error('surat_izin') is-invalid @enderror"
                            value="{{ old('surat_izin') }}">
                        @error('surat_izin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    <a href="{{ route('cuti-perizinan.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </x-page>
@endsection
