@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Permohonan Izin</h1>
    <a href="{{ route('cuti-perizinan.create') }}" class="btn btn-primary mb-3">Ajukan Izin</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Keterangan</th>
                <th>Jenis</th>
                <th>Status</th>
                <th>Disetujui Oleh</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cutiPerizinans as $cutiPerizinan)
            <tr>
                <td>{{ $cutiPerizinan->id }}</td>
                <td>{{ $cutiPerizinan->user->name }}</td>
                <td>{{ $cutiPerizinan->tanggal_mulai }}</td>
                <td>{{ $cutiPerizinan->tanggal_selesai }}</td>
                <td>{{ $cutiPerizinan->keterangan }}</td>
                <td>{{ $cutiPerizinan->jenis }}</td>
                <td>{{ $cutiPerizinan->status_pengajuan }}</td>
                <td>{{ $cutiPerizinan->disetujuiOleh->name ?? '-' }}</td>
                <td>
                    <a href="{{ route('cuti-perizinan.show', $cutiPerizinan) }}" class="btn btn-info">Lihat</a>
                    <a href="{{ route('cuti-perizinan.edit', $cutiPerizinan) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('cuti-perizinan.destroy', $cutiPerizinan) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                    @if ($cutiPerizinan->status_pengajuan === 'diajukan')
                        <form action="{{ route('cuti-perizinan.approve', $cutiPerizinan) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success">Setujui</button>
                        </form>
                        <form action="{{ route('cuti-perizinan.reject', $cutiPerizinan) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger">Tolak</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection