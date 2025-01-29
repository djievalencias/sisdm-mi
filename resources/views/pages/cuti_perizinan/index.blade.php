@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Data Permohonan Izin</h1>

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('cuti-perizinan.hasil') }}" class="btn btn-primary">Lihat Hasil Permohonan</a>
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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cutiPerizinans as $izin)
                    <tr>
                        <td>{{ $izin->user->nama }}</td>
                        <td>{{ $izin->tanggal_mulai }}</td>
                        <td>{{ $izin->tanggal_selesai }}</td>
                        <td>{{ $izin->keterangan }}</td>
                        <td>
                            <span class="badge {{ $izin->status_pengajuan == 'diajukan' ? 'bg-warning' : ($izin->status_pengajuan == 'disetujui' ? 'bg-success' : 'bg-danger') }}">
                                {{ ucfirst($izin->status_pengajuan) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('cuti-perizinan.show', $izin->id) }}" class="btn btn-info btn-sm">Detail</a>
                            <a href="{{ route('cuti-perizinan.edit', $izin->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('cuti-perizinan.destroy', $izin->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                            <form action="{{ route('cuti-perizinan.approve', $izin->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                            </form>
                            
                            <form action="{{ route('cuti-perizinan.reject', $izin->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-sm">Reject</button>
                            </form>
                            
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
