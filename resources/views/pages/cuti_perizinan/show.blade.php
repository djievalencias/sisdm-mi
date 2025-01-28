@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detail Permohonan Izin</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">ID: {{ $cutiPerizinan->id }}</h5>
            <p class="card-text">User: {{ $cutiPerizinan->user->name }}</p>
            <p class="card-text">Tanggal Mulai: {{ $cutiPerizinan->tanggal_mulai }}</p>
            <p class="card-text">Tanggal Selesai: {{ $cutiPerizinan->tanggal_selesai }}</p>
            <p class="card-text">Keterangan: {{ $cutiPerizinan->keterangan }}</p>
            <p class="card-text">Jenis: {{ $cutiPerizinan->jenis }}</p>
            <p class="card-text">Status Pengajuan: {{ $cutiPerizinan->status_pengajuan }}</p>
            <p class="card-text">Disetujui Oleh: {{ $cutiPerizinan->disetujuiOleh->name ?? '-' }}</p>
            <p class="card-text">Surat Izin: {{ $cutiPerizinan->surat_izin }}</p>
        </div>
    </div>
    <a href="{{ route('cuti-perizinan.index') }}" class="btn btn-primary mt-3">Kembali</a>
</div>
@endsection