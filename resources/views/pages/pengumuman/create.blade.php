@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Pengumuman</h1>
    <form action="{{ route('pengumuman.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="judul">Judul</label>
            <input type="text" name="judul" id="judul" class="form-control" value="{{ old('judul') }}">
        </div>
        <div class="form-group">
            <label for="pesan">Pesan</label>
            <textarea name="pesan" id="pesan" class="form-control">{{ old('pesan') }}</textarea>
        </div>
        <div class="form-group">
            <label for="foto">Foto</label>
            <input type="file" name="foto" id="foto" class="form-control">
        </div>
        <div class="form-group">
            <label for="departemen">Distribusi Departemen</label>
            <select name="departemen[]" id="departemen" class="form-control" multiple>
                @foreach ($departemen as $item)
                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
