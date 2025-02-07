{{-- resources/views/attendance/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Attendance #{{ $attendance->id }}</h1>
    <form action="{{ route('attendance.update', $attendance->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="id_user" class="form-label">User</label>
            <select name="id_user" id="id_user" class="form-control">
                <option value="">--Pilih User--</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" 
                        {{ $attendance->id_user == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            @error('id_user') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" 
                   value="{{ old('tanggal',$attendance->tanggal->format('Y-m-d')) }}">
            @error('tanggal') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status (Checkout?)</label>
            <select name="status" id="status" class="form-control">
                <option value="0" {{ !$attendance->status ? 'selected' : '' }}>Belum Checkout (false)</option>
                <option value="1" {{ $attendance->status ? 'selected' : '' }}>Sudah Checkout (true)</option>
            </select>
            @error('status') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="hari_kerja" class="form-label">Hari Kerja</label>
            <input type="number" step="0.01" name="hari_kerja" id="hari_kerja" class="form-control" 
                   value="{{ old('hari_kerja',$attendance->hari_kerja) }}">
            @error('hari_kerja') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="jumlah_jam_lembur" class="form-label">Jumlah Jam Lembur</label>
            <input type="number" step="0.01" name="jumlah_jam_lembur" id="jumlah_jam_lembur" class="form-control" 
                   value="{{ old('jumlah_jam_lembur',$attendance->jumlah_jam_lembur) }}">
            @error('jumlah_jam_lembur') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="is_tanggal_merah" class="form-label">Tanggal Merah?</label>
            <select name="is_tanggal_merah" id="is_tanggal_merah" class="form-control">
                <option value="0" {{ !$attendance->is_tanggal_merah ? 'selected' : '' }}>Tidak</option>
                <option value="1" {{ $attendance->is_tanggal_merah ? 'selected' : '' }}>Ya</option>
            </select>
            @error('is_tanggal_merah') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Perbarui</button>
    </form>
</div>
@endsection
