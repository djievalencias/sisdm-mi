@extends('layouts.app')

@section('content')
    <x-page :title="__('Edit Department')" :breadcrumb="__('Departments')">
        <div class="card">
            <form action="{{ route('departemen.update', $departemen->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="nama">{{ __('Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama', $departemen->nama) }}" required>
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="id_kantor">{{ __('Office') }}</label>
                        <select name="id_kantor" id="id_kantor" class="form-control @error('id_kantor') is-invalid @enderror">
                            <option value="">{{ __('Select Office') }}</option>
                            @foreach ($kantor as $k)
                                <option value="{{ $k->id }}" {{ old('id_kantor', $departemen->id_kantor) == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                            @endforeach
                        </select>
                        @error('id_kantor')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    <a href="{{ route('departemen.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </x-page>
@endsection
