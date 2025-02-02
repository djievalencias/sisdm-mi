@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Hasil Permohonan Izin</h1>

    <!-- Filter Status -->
    <div class="mb-3">
        <form method="GET" action="{{ route('cuti-perizinan.hasil') }}" class="d-flex">
            <select name="status" class="form-select me-2">
                <option value="">Semua</option>
                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Nama</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Disetujui Oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cutiPerizinans as $izin)
                    <tr>
                        <td>{{ $izin->user->nama }}</td>
                        <td>{{ $izin->tanggal_mulai }}</td>
                        <td>{{ $izin->tanggal_selesai }}</td>
                        <td>{{ $izin->keterangan }}</td>
                        <td>
                            <span class="badge {{ $izin->status_pengajuan == 'disetujui' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($izin->status_pengajuan) }}
                            </span>
                        </td>
                        <td>{{ $izin->disetujuiOleh->nama ?? 'Admin' }}</td>
                        <td>
                            <form action="{{ route('cuti-perizinan.undo', $izin->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm">Undo</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-muted">Belum ada hasil permohonan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <a href="{{ route('cuti-perizinan.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
