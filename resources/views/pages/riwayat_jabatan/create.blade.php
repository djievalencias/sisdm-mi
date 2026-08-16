@extends('layouts.app')

@section('content')
    <x-page :title="__('Add Job History') . ' — ' . $user->nama" :breadcrumb="__('Job History')">
        <div class="card">
            <form action="{{ route('riwayat_jabatan.store', $user->id) }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <div class="card-body">
                    <div class="form-group">
                        <label for="jabatan">{{ __('Position') }} <span class="text-danger">*</span></label>
                        <select name="id_jabatan" id="jabatan" class="form-control @error('id_jabatan') is-invalid @enderror" required>
                            <option value="">{{ __('Select Position') }}</option>
                            @foreach ($jabatanList as $jabatan)
                                <option value="{{ $jabatan->id }}"
                                    data-kantor="{{ $jabatan->grup->departemen->kantor->nama ?? '' }}"
                                    data-departemen="{{ $jabatan->grup->departemen->nama ?? '' }}"
                                    data-grup="{{ $jabatan->grup->nama ?? '' }}"
                                    {{ old('id_jabatan') == $jabatan->id ? 'selected' : '' }}>
                                    {{ $jabatan->nama }}{{ $jabatan->grup ? ' — ' . $jabatan->grup->nama : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="kantor">{{ __('Office') }}</label>
                            <input type="text" id="kantor" class="form-control" readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="departemen">{{ __('Department') }}</label>
                            <input type="text" id="departemen" class="form-control" readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="grup">{{ __('Group') }}</label>
                            <input type="text" id="grup" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="tanggal_mulai">{{ __('Start Date') }} <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                value="{{ old('tanggal_mulai') }}" required>
                            @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tanggal_selesai">{{ __('End Date (Optional)') }}</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                value="{{ old('tanggal_selesai') }}">
                            @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    <a href="{{ route('user.show', $user->id) }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </x-page>

    <script>
        document.getElementById('jabatan').addEventListener('change', function () {
            var selectedOption = this.options[this.selectedIndex];
            document.getElementById('kantor').value = selectedOption.getAttribute('data-kantor') || '';
            document.getElementById('departemen').value = selectedOption.getAttribute('data-departemen') || '';
            document.getElementById('grup').value = selectedOption.getAttribute('data-grup') || '';
        });
    </script>
@endsection
