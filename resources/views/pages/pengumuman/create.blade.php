@extends('layouts.app')

@section('content')
    <x-page :title="__('Add Announcement')" :breadcrumb="__('Announcements')">
        <div class="card">
            <form action="{{ route('pengumuman.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="judul">{{ __('Title') }} <span class="text-danger">*</span></label>
                        <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror"
                            value="{{ old('judul') }}" required>
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="pesan">{{ __('Message') }} <span class="text-danger">*</span></label>
                        <textarea name="pesan" id="pesan" rows="4" class="form-control @error('pesan') is-invalid @enderror" required>{{ old('pesan') }}</textarea>
                        @error('pesan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="foto">{{ __('Photo') }}</label>
                        <input type="file" name="foto" id="foto" accept="image/*" class="form-control-file @error('foto') is-invalid @enderror">
                        @error('foto')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="departemen">{{ __('Department Distribution') }}</label>
                        <select name="departemen[]" id="departemen" class="form-control @error('departemen') is-invalid @enderror" multiple size="6">
                            @foreach ($departemen as $item)
                                <option value="{{ $item->id }}" {{ in_array($item->id, old('departemen', [])) ? 'selected' : '' }}>{{ $item->nama }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">{{ __('Hold Ctrl (Cmd on Mac) to select multiple departments.') }}</small>
                        @error('departemen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    <a href="{{ route('pengumuman.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </x-page>
@endsection
