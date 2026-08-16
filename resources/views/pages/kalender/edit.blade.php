@extends('layouts.app')

@section('content')
    <x-page :title="__('Edit Event')" :breadcrumb="__('Work Calendar')">
        <div class="card">
            <form action="{{ route('kalender.update', $kalender->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="judul">{{ __('Event Title') }} <span class="text-danger">*</span></label>
                        <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror"
                            value="{{ old('judul', $kalender->judul) }}" required>
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="tanggal_mulai">{{ __('Start Date') }} <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                value="{{ old('tanggal_mulai', $kalender->tanggal_mulai) }}" required>
                            @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tanggal_selesai">{{ __('End Date (Optional)') }}</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                value="{{ old('tanggal_selesai', $kalender->tanggal_selesai) }}">
                            @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tipe">{{ __('Event Type') }} <span class="text-danger">*</span></label>
                        <select name="tipe" id="tipe" class="form-control @error('tipe') is-invalid @enderror" required>
                            <option value="hari_libur" {{ old('tipe', $kalender->tipe) == 'hari_libur' ? 'selected' : '' }}>{{ __('Holiday') }}</option>
                            <option value="meeting" {{ old('tipe', $kalender->tipe) == 'meeting' ? 'selected' : '' }}>{{ __('Meeting') }}</option>
                            <option value="acara" {{ old('tipe', $kalender->tipe) == 'acara' ? 'selected' : '' }}>{{ __('Event') }}</option>
                            <option value="lainnya" {{ old('tipe', $kalender->tipe) == 'lainnya' ? 'selected' : '' }}>{{ __('Other') }}</option>
                        </select>
                        @error('tipe')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="repeat_type">{{ __('Repeat') }}</label>
                            <select name="repeat_type" id="repeat_type" class="form-control @error('repeat_type') is-invalid @enderror">
                                <option value="never" {{ old('repeat_type', $kalender->repeat_type ?? 'never') == 'never' ? 'selected' : '' }}>{{ __('Never') }}</option>
                                <option value="weekly" {{ old('repeat_type', $kalender->repeat_type ?? '') == 'weekly' ? 'selected' : '' }}>{{ __('Weekly') }}</option>
                                <option value="monthly" {{ old('repeat_type', $kalender->repeat_type ?? '') == 'monthly' ? 'selected' : '' }}>{{ __('Monthly') }}</option>
                                <option value="yearly" {{ old('repeat_type', $kalender->repeat_type ?? '') == 'yearly' ? 'selected' : '' }}>{{ __('Yearly') }}</option>
                            </select>
                            @error('repeat_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="repeat_until">{{ __('Repeat Until') }}</label>
                            <input type="date" name="repeat_until" id="repeat_until" class="form-control @error('repeat_until') is-invalid @enderror"
                                value="{{ old('repeat_until', $kalender->repeat_until ?? '') }}">
                            @error('repeat_until')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    <a href="{{ route('kalender.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </x-page>
@endsection
