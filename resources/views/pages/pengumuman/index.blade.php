@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daftar Pengumuman</h2>
    <a href="{{ route('pengumuman.create') }}" class="btn btn-primary">Tambah Pengumuman</a>
    <table class="table">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Pesan</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pengumuman as $item)
            <tr>
                <td>{{ $item->judul }}</td>
                <td>{{ $item->pesan }}</td>
                <td>
                    @if ($item->foto)
                    <img src="{{ asset('storage/' . $item->foto) }}" alt="foto" width="100">
                    @endif
                </td>
                <td>
                    <a href="{{ route('pengumuman.edit', $item) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('pengumuman.destroy', $item) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
