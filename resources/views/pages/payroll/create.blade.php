{{-- resources/views/payroll/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Buat Payroll Baru</h1>
    <form action="{{ route('payroll.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="id_user" class="form-label">User</label>
            <select name="id_user" id="id_user" class="form-control">
                <option value="">--Pilih User--</option>
                @foreach($users as $user)
                <option value="{{ $user->id }}" {{ old('id_user') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
                @endforeach
            </select>
            @error('id_user') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="tanggal_payroll" class="form-label">Tanggal Payroll</label>
            <input type="date" name="tanggal_payroll" class="form-control" value="{{ old('tanggal_payroll') }}">
            @error('tanggal_payroll') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="gaji_pokok" class="form-label">Gaji Pokok</label>
            <input type="number" step="0.01" name="gaji_pokok" class="form-control" value="{{ old('gaji_pokok',0) }}">
            @error('gaji_pokok') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="upah_lembur" class="form-label">Upah Lembur</label>
            <input type="number" step="0.01" name="upah_lembur" class="form-control" value="{{ old('upah_lembur',0) }}">
            @error('upah_lembur') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="gaji_tgl_merah" class="form-label">Gaji Tgl Merah</label>
            <input type="number" step="0.01" name="gaji_tgl_merah" class="form-control" value="{{ old('gaji_tgl_merah',0) }}">
            @error('gaji_tgl_merah') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="upah_lembur_tgl_merah" class="form-label">Upah Lembur Tgl Merah</label>
            <input type="number" step="0.01" name="upah_lembur_tgl_merah" class="form-control" value="{{ old('upah_lembur_tgl_merah',0) }}">
            @error('upah_lembur_tgl_merah') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="iuran_bpjs_kantor" class="form-label">Iuran BPJS Kantor</label>
            <input type="number" step="0.01" name="iuran_bpjs_kantor" class="form-control" value="{{ old('iuran_bpjs_kantor',0) }}">
            @error('iuran_bpjs_kantor') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="iuran_bpjs_karyawan" class="form-label">Iuran BPJS Karyawan</label>
            <input type="number" step="0.01" name="iuran_bpjs_karyawan" class="form-control" value="{{ old('iuran_bpjs_karyawan',0) }}">
            @error('iuran_bpjs_karyawan') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
