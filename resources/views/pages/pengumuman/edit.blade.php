@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Pengumuman</h1>
    <form action="{{ route('pengumuman.update', $pengumuman) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="judul">Judul</label>
            <input type="text" name="judul" id="judul" class="form-control" value="{{ $pengumuman->judul }}">
        </div>
        <div class="form-group">
            <label for="pesan">Pesan</label>
            <textarea name="pesan" id="pesan" class="form-control">{{ $pengumuman->pesan }}</textarea>
        </div>
        <div class="form-group">
            <label for="foto">Foto</label>
            <input type="file" name="foto" id="foto" class="form-control">
            @if ($pengumuman->foto)
                <img src="{{ asset('storage/' . $pengumuman->foto) }}" alt="foto" width="100">
            @endif
        </div>
        <div class="form-group">
            <label for="departemen">Distribusi Departemen</label>
            <select name="departemen[]" id="departemen" class="form-control" multiple>
                @foreach ($departemen as $item)
                    <option value="{{ $item->id }}" {{ in_array($item->id, $distribusi) ? 'selected' : '' }}>
                        {{ $item->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
