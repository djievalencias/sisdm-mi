@extends('layouts.app')

@section('content')
    <x-page :title="__('Add Position')" :breadcrumb="__('Positions')">
        <div class="card">
            <form action="{{ route('jabatan.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="nama">{{ __('Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama') }}" required>
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="id_grup">{{ __('Group') }}</label>
                        <select name="id_grup" id="id_grup" class="form-control @error('id_grup') is-invalid @enderror">
                            <option value="">{{ __('Select Group') }}</option>
                            @foreach ($grup as $g)
                                <option value="{{ $g->id }}" {{ old('id_grup') == $g->id ? 'selected' : '' }}>{{ $g->nama }}</option>
                            @endforeach
                        </select>
                        @error('id_grup')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="description">{{ __('Description') }}</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    <a href="{{ route('jabatan.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </x-page>
@endsection
