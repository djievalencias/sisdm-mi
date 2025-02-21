{{-- resources/views/attendance/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detail Attendance #{{ $attendance->id }}</h1>

    <div class="mb-3">
        <strong>User: </strong> {{ $attendance->user->name ?? '-' }}
    </div>
    <div class="mb-3">
        <strong>Tanggal: </strong> {{ $attendance->tanggal }}
    </div>
    <div class="mb-3">
        <strong>Status (checkout?): </strong> 
        {{ $attendance->status ? 'Sudah Checkout' : 'Belum' }}
    </div>
    <div class="mb-3">
        <strong>Hari Kerja: </strong> {{ $attendance->hari_kerja }}
    </div>
    <div class="mb-3">
        <strong>Jam Lembur: </strong> {{ $attendance->jumlah_jam_lembur }}
    </div>
    <div class="mb-3">
        <strong>Tanggal Merah?: </strong> 
        {{ $attendance->is_tanggal_merah ? 'Ya' : 'Tidak' }}
    </div>

    <hr>
    <h4>Attendance Details:</h4>
    @if($attendance->detail->count() > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Type (in/out)</th>
                    <th>Long</th>
                    <th>Lat</th>
                    <th>Address</th>
                    <th>Photo (url)</th>
                </tr>
            </thead>
            <tbody>
            @foreach($attendance->detail as $dt)
                <tr>
                    <td>{{ $dt->type }}</td>
                    <td>{{ $dt->long }}</td>
                    <td>{{ $dt->lat }}</td>
                    <td>{{ $dt->address }}</td>
                    <td>
                        @if($dt->photo)
                            <a href="{{ asset('' . $dt->photo) }}" target="_blank">Lihat</a>
                        @else
                            Tidak ada foto
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>Tidak ada detail.</p>
    @endif

    <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
