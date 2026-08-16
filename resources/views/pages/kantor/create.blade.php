@extends('layouts.app')

@section('content')
    <x-page :title="__('Add Office')" :breadcrumb="__('Offices')">
        <div class="card">
            <form action="{{ route('kantor.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="nama">{{ __('Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama') }}" required>
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="alamat">{{ __('Address') }} <span class="text-danger">*</span></label>
                        <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" required>{{ old('alamat') }}</textarea>
                        @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="koordinat_x">{{ __('Longitude (X)') }} <span class="text-danger">*</span></label>
                            <input type="number" name="koordinat_x" id="koordinat_x" step="any" min="-180" max="180"
                                class="form-control @error('koordinat_x') is-invalid @enderror" value="{{ old('koordinat_x') }}" required>
                            @error('koordinat_x')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="koordinat_y">{{ __('Latitude (Y)') }} <span class="text-danger">*</span></label>
                            <input type="number" name="koordinat_y" id="koordinat_y" step="any" min="-90" max="90"
                                class="form-control @error('koordinat_y') is-invalid @enderror" value="{{ old('koordinat_y') }}" required>
                            @error('koordinat_y')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="radius">{{ __('Radius (meters)') }} <span class="text-danger">*</span></label>
                            <input type="number" name="radius" id="radius" min="0" step="any"
                                class="form-control @error('radius') is-invalid @enderror" value="{{ old('radius') }}" required>
                            @error('radius')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="id_manager">{{ __('Manager') }}</label>
                        <select name="id_manager" id="id_manager" class="form-control @error('id_manager') is-invalid @enderror">
                            <option value="">{{ __('Select Manager') }}</option>
                            @foreach ($managers as $manager)
                                <option value="{{ $manager->id }}" {{ old('id_manager') == $manager->id ? 'selected' : '' }}>{{ $manager->nama }}</option>
                            @endforeach
                        </select>
                        @error('id_manager')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    <a href="{{ route('kantor.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </x-page>
@endsection
