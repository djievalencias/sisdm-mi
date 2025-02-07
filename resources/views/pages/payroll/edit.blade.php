{{-- resources/views/payroll/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Payroll #{{ $payroll->id }}</h1>
    <form action="{{ route('payroll.update', $payroll->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="id_user" class="form-label">User</label>
            <select name="id_user" id="id_user" class="form-control">
                @foreach($users as $user)
                <option value="{{ $user->id }}" 
                    {{ $payroll->id_user == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
                @endforeach
            </select>
            @error('id_user') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="tanggal_payroll" class="form-label">Tanggal Payroll</label>
            <input type="date" name="tanggal_payroll" class="form-control" 
                   value="{{ old('tanggal_payroll', $payroll->tanggal_payroll) }}">
            @error('tanggal_payroll') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="gaji_pokok" class="form-label">Gaji Pokok</label>
            <input type="number" step="0.01" name="gaji_pokok" class="form-control" 
                   value="{{ old('gaji_pokok',$payroll->gaji_pokok) }}">
            @error('gaji_pokok') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="upah_lembur" class="form-label">Upah Lembur</label>
            <input type="number" step="0.01" name="upah_lembur" class="form-control" 
                   value="{{ old('upah_lembur',$payroll->upah_lembur) }}">
            @error('upah_lembur') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="gaji_tgl_merah" class="form-label">Gaji Tgl Merah</label>
            <input type="number" step="0.01" name="gaji_tgl_merah" class="form-control" 
                   value="{{ old('gaji_tgl_merah',$payroll->gaji_tgl_merah) }}">
            @error('gaji_tgl_merah') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="upah_lembur_tgl_merah" class="form-label">Upah Lembur Tgl Merah</label>
            <input type="number" step="0.01" name="upah_lembur_tgl_merah" class="form-control" 
                   value="{{ old('upah_lembur_tgl_merah',$payroll->upah_lembur_tgl_merah) }}">
            @error('upah_lembur_tgl_merah') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="iuran_bpjs_kantor" class="form-label">Iuran BPJS Kantor</label>
            <input type="number" step="0.01" name="iuran_bpjs_kantor" class="form-control" 
                   value="{{ old('iuran_bpjs_kantor',$payroll->iuran_bpjs_kantor) }}">
            @error('iuran_bpjs_kantor') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="iuran_bpjs_karyawan" class="form-label">Iuran BPJS Karyawan</label>
            <input type="number" step="0.01" name="iuran_bpjs_karyawan" class="form-control" 
                   value="{{ old('iuran_bpjs_karyawan',$payroll->iuran_bpjs_karyawan) }}">
            @error('iuran_bpjs_karyawan') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="is_reviewed" class="form-label">Is Reviewed?</label>
            <select name="is_reviewed" class="form-control">
                <option value="0" {{ !$payroll->is_reviewed ? 'selected' : '' }}>No</option>
                <option value="1" {{ $payroll->is_reviewed ? 'selected' : '' }}>Yes</option>
            </select>
            @error('is_reviewed') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status (Paid?)</label>
            <select name="status" class="form-control">
                <option value="0" {{ !$payroll->status ? 'selected' : '' }}>Belum Bayar</option>
                <option value="1" {{ $payroll->status ? 'selected' : '' }}>Sudah Bayar</option>
            </select>
            @error('status') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Perbarui</button>
    </form>
</div>
@endsection
