@extends('layouts.app')

@section('content')
    <x-page :title="__('Edit Attendance') . ' #' . $attendance->id" :breadcrumb="__('Attendance')">
        <div class="card">
            <form action="{{ route('attendance.update', $attendance->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="id_user">{{ __('Employee') }} <span class="text-danger">*</span></label>
                        <select name="id_user" id="id_user" class="form-control @error('id_user') is-invalid @enderror" required>
                            <option value="">{{ __('Select employee') }}</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ old('id_user', $attendance->id_user) == $user->id ? 'selected' : '' }}>{{ $user->nama }}</option>
                            @endforeach
                        </select>
                        @error('id_user')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="tanggal">{{ __('Date') }} <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                value="{{ old('tanggal', $attendance->tanggal->format('Y-m-d')) }}" required>
                            @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="status">{{ __('Clocked out?') }}</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="0" {{ !old('status', $attendance->status) ? 'selected' : '' }}>{{ __('Not clocked out') }}</option>
                                <option value="1" {{ old('status', $attendance->status) ? 'selected' : '' }}>{{ __('Clocked out') }}</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="hari_kerja">{{ __('Workdays') }}</label>
                            <input type="number" step="0.01" name="hari_kerja" id="hari_kerja"
                                class="form-control @error('hari_kerja') is-invalid @enderror"
                                value="{{ old('hari_kerja', $attendance->hari_kerja) }}">
                            @error('hari_kerja')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="jumlah_jam_lembur">{{ __('Overtime Hours') }}</label>
                            <input type="number" step="0.01" name="jumlah_jam_lembur" id="jumlah_jam_lembur"
                                class="form-control @error('jumlah_jam_lembur') is-invalid @enderror"
                                value="{{ old('jumlah_jam_lembur', $attendance->jumlah_jam_lembur) }}">
                            @error('jumlah_jam_lembur')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="is_tanggal_merah">{{ __('Holiday?') }}</label>
                            <select name="is_tanggal_merah" id="is_tanggal_merah" class="form-control @error('is_tanggal_merah') is-invalid @enderror">
                                <option value="0" {{ !old('is_tanggal_merah', $attendance->is_tanggal_merah) ? 'selected' : '' }}>{{ __('No') }}</option>
                                <option value="1" {{ old('is_tanggal_merah', $attendance->is_tanggal_merah) ? 'selected' : '' }}>{{ __('Yes') }}</option>
                            </select>
                            @error('is_tanggal_merah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    <a href="{{ route('attendance.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </x-page>
@endsection
