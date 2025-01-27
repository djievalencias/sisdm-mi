@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1>Edit Kantor</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <a href="{{ route('kantor.index') }}" class="btn btn-secondary mb-3">Back</a>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('kantor.update', $kantor->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" class="form-control" value="{{ $kantor->nama }}" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" class="form-control" required>{{ $kantor->alamat }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="koordinat_x">Koordinat X</label>
                        <input type="number" name="koordinat_x" class="form-control" step="any" value="{{ $kantor->koordinat_x }}" required>
                    </div>
                    <div class="form-group">
                        <label for="koordinat_y">Koordinat Y</label>
                        <input type="number" name="koordinat_y" class="form-control" step="any" value="{{ $kantor->koordinat_y }}" required>
                    </div>
                    <div class="form-group">
                        <label for="radius">Radius</label>
                        <input type="number" name="radius" class="form-control" value="{{ $kantor->radius }}" required>
                    </div>
                    <div class="form-group">
                        <label for="id_manager">Manager</label>
                        <select name="id_manager" class="form-control">
                            <option value="">Pilih Manager</option>
                            @foreach ($managers as $manager)
                                <option value="{{ $manager->id }}">{{ $manager->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
