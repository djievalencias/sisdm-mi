{{-- resources/views/attendance/index.blade.php --}}
@extends('layouts.app') 
{{-- layouts.app adalah layout utama Anda, sesuaikan dengan layout yang tersedia --}}

@section('content')
<div class="container">
    <h1>Daftar Attendance</h1>
    <a href="{{ route('attendance.create') }}" class="btn btn-primary">Buat Attendance Baru</a>

    @if (session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama User</th>
                <th>Tanggal</th>
                <th>Status (checkout?)</th>
                <th>Hari Kerja</th>
                <th>Jam Lembur</th>
                <th>Tgl Merah?</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->id }}</td>
                    <td>{{ $attendance->user->nama ?? 'No User' }}</td>
                    <td>{{ $attendance->tanggal->format('Y-m-d H:i:s') }}</td>
                    <td>{{ $attendance->status ? 'Sudah Check Out' : 'Belum' }}</td>
                    <td>{{ $attendance->hari_kerja }}</td>
                    <td>{{ $attendance->jumlah_jam_lembur }}</td>
                    <td>{{ $attendance->is_tanggal_merah ? 'Ya' : 'Tidak' }}</td>
                    <td>
                        <a href="{{ route('attendance.show', $attendance->id) }}" class="btn btn-sm btn-info">Detail</a>
                        <a href="{{ route('attendance.edit', $attendance->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('attendance.destroy', $attendance->id) }}" method="POST" style="display:inline-block">
                            @csrf 
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Yakin hapus?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Belum ada data Attendance.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection
