@extends('layouts.app')

@section('content')
    <x-page :title="__('Edit Group')" :breadcrumb="__('Groups')">
        <div class="card">
            <form action="{{ route('grup.update', $grup->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="nama">{{ __('Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama', $grup->nama) }}" required>
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="id_departemen">{{ __('Department') }}</label>
                        <select name="id_departemen" id="id_departemen" class="form-control @error('id_departemen') is-invalid @enderror">
                            <option value="">{{ __('Select Department') }}</option>
                            @foreach ($departemen as $d)
                                <option value="{{ $d->id }}" {{ old('id_departemen', $grup->id_departemen) == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>
                            @endforeach
                        </select>
                        @error('id_departemen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    <a href="{{ route('grup.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </x-page>
@endsection
