@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Edit Permohonan Izin</h1>

    <div class="card shadow-lg">
        <div class="card-body">
            <form action="{{ route('cuti-perizinan.update', $cutiPerizinan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="id_user" class="form-label">User</label>
                    <select name="id_user" class="form-select" required>
                        @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $cutiPerizinan->id_user == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control" value="{{ $cutiPerizinan->tanggal_mulai }}" required>
                </div>

                <div class="mb-3">
                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control" value="{{ $cutiPerizinan->tanggal_selesai }}" required>
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" required>{{ $cutiPerizinan->keterangan }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="jenis" class="form-label">Jenis</label>
                    <select name="jenis" class="form-select" required>
                        <option value="izin" {{ $cutiPerizinan->jenis == 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="alpa" {{ $cutiPerizinan->jenis == 'alpa' ? 'selected' : '' }}>Alpa</option>
                        <option value="sakit" {{ $cutiPerizinan->jenis == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('cuti-perizinan.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
