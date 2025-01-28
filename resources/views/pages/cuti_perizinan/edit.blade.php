@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Permohonan Izin</h1>
    <form action="{{ route('cuti-perizinan.update', $cutiPerizinan) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="id_user">User</label>
            <select name="id_user" id="id_user" class="form-control" required>
                @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ $cutiPerizinan->id_user == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="tanggal_mulai">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="{{ $cutiPerizinan->tanggal_mulai }}" required>
        </div>
        <div class="form-group">
            <label for="tanggal_selesai">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="{{ $cutiPerizinan->tanggal_selesai }}" required>
        </div>
        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control" required>{{ $cutiPerizinan->keterangan }}</textarea>
        </div>
        <div class="form-group">
            <label for="jenis">Jenis</label>
            <select name="jenis" id="jenis" class="form-control" required>
                <option value="izin" {{ $cutiPerizinan->jenis == 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="alpa" {{ $cutiPerizinan->jenis == 'alpa' ? 'selected' : '' }}>Alpa</option>
                <option value="sakit" {{ $cutiPerizinan->jenis == 'sakit' ? 'selected' : '' }}>Sakit</option>
            </select>
        </div>
        <div class="form-group">
            <label for="status_pengajuan">Status Pengajuan</label>
            <select name="status_pengajuan" id="status_pengajuan" class="form-control" required>
                <option value="diajukan" {{ $cutiPerizinan->status_pengajuan == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                <option value="disetujui" {{ $cutiPerizinan->status_pengajuan == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="ditolak" {{ $cutiPerizinan->status_pengajuan == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <div class="form-group">
            <label for="disetujui_oleh">Disetujui Oleh</label>
            <select name="disetujui_oleh" id="disetujui_oleh" class="form-control">
                <option value="">Pilih User</option>
                @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ $cutiPerizinan->disetujui_oleh == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="surat_izin">Surat Izin</label>
            <input type="text" name="surat_izin" id="surat_izin" class="form-control" value="{{ $cutiPerizinan->surat_izin }}">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection