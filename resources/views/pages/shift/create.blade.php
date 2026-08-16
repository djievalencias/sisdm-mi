@extends('layouts.app')

@section('content')
    <x-page :title="__('Add Shift')" :breadcrumb="__('Shifts')">
        <div class="card">
            <form action="{{ route('shift.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="nama">{{ __('Shift Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama') }}" required>
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="waktu_mulai">{{ __('Start Time') }} <span class="text-danger">*</span></label>
                            <input type="time" name="waktu_mulai" id="waktu_mulai" class="form-control @error('waktu_mulai') is-invalid @enderror"
                                value="{{ old('waktu_mulai') }}" required>
                            @error('waktu_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="waktu_selesai">{{ __('End Time') }} <span class="text-danger">*</span></label>
                            <input type="time" name="waktu_selesai" id="waktu_selesai" class="form-control @error('waktu_selesai') is-invalid @enderror"
                                value="{{ old('waktu_selesai') }}" required>
                            @error('waktu_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Days') }}</label>
                        @foreach (['senin' => __('Monday'), 'selasa' => __('Tuesday'), 'rabu' => __('Wednesday'), 'kamis' => __('Thursday'), 'jumat' => __('Friday'), 'sabtu' => __('Saturday'), 'minggu' => __('Sunday')] as $field => $label)
                            <div class="form-check">
                                <input type="checkbox" name="{{ $field }}" id="{{ $field }}" value="1" class="form-check-input" {{ old($field) ? 'checked' : '' }}>
                                <label class="form-check-label" for="{{ $field }}">{{ $label }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="tanggal_mulai">{{ __('Start Date') }}</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                value="{{ old('tanggal_mulai') }}">
                            @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tanggal_berakhir">{{ __('End Date') }}</label>
                            <input type="date" name="tanggal_berakhir" id="tanggal_berakhir" class="form-control @error('tanggal_berakhir') is-invalid @enderror"
                                value="{{ old('tanggal_berakhir') }}">
                            @error('tanggal_berakhir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">{{ __('Description') }}</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    <a href="{{ route('shift.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </x-page>
@endsection
