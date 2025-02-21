@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Detail Permohonan Izin</h1>
    
    <div class="card shadow-lg">
        <div class="card-body">
            <h5><strong>Nama:</strong> {{ $cutiPerizinan->user->nama }}</h5>
            <p><strong>Tanggal Mulai:</strong> {{ $cutiPerizinan->tanggal_mulai }}</p>
            <p><strong>Tanggal Selesai:</strong> {{ $cutiPerizinan->tanggal_selesai }}</p>
            <p><strong>Keterangan:</strong> {{ $cutiPerizinan->keterangan }}</p>
            <p><strong>Jenis:</strong> {{ ucfirst($cutiPerizinan->jenis) }}</p>
            <p><strong>Status Pengajuan:</strong>
                <span class="badge {{ $cutiPerizinan->status_pengajuan == 'diajukan' ? 'bg-warning' : ($cutiPerizinan->status_pengajuan == 'disetujui' ? 'bg-success' : 'bg-danger') }}">
                    {{ ucfirst($cutiPerizinan->status_pengajuan) }}
                </span>
            </p>
            <p><strong>Disetujui Oleh:</strong> {{ $cutiPerizinan->disetujuiOleh->nama ?? 'Belum disetujui' }}</p>
            <p><strong>Surat Izin:</strong> 
                @if ($cutiPerizinan->surat_izin)
                    <a href="{{ asset('storage/'.$cutiPerizinan->surat_izin) }}" target="_blank" class="btn btn-primary">Lihat File</a>
                @else
                    <span class="text-muted">Tidak ada file</span>
                @endif
            </p>
        </div>
    </div>

    <a href="{{ route('cuti-perizinan.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
