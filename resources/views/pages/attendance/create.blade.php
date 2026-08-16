@extends('layouts.app')

@section('content')
    <x-page :title="__('Add Attendance')" :breadcrumb="__('Attendance')">
        <div class="card">
            <form action="{{ route('attendance.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="type">{{ __('Type') }} <span class="text-danger">*</span></label>
                        <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                            <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>{{ __('IN (Check-in)') }}</option>
                            <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>{{ __('OUT (Check-out)') }}</option>
                        </select>
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="long">{{ __('Longitude') }} <span class="text-danger">*</span></label>
                            <input type="number" step="any" min="-180" max="180" name="long" id="long"
                                class="form-control @error('long') is-invalid @enderror" value="{{ old('long') }}" required>
                            @error('long')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lat">{{ __('Latitude') }} <span class="text-danger">*</span></label>
                            <input type="number" step="any" min="-90" max="90" name="lat" id="lat"
                                class="form-control @error('lat') is-invalid @enderror" value="{{ old('lat') }}" required>
                            @error('lat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address">{{ __('Address') }} <span class="text-danger">*</span></label>
                        <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror"
                            value="{{ old('address') }}" required>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="photo">{{ __('Photo') }} <span class="text-danger">*</span></label>
                        <input type="file" name="photo" id="photo" accept="image/*"
                            class="form-control-file @error('photo') is-invalid @enderror" required>
                        @error('photo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
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
