@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <h1>Add Kantor</h1>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <a href="{{ route('kantor.index') }}" class="btn btn-secondary mb-3">Back</a>

            <div class="card">
                <div class="card-body">
                {{-- Display Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('kantor.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea name="alamat" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="koordinat_x">Koordinat X</label>
                            <input type="number" name="koordinat_x" class="form-control" step="any" required>
                        </div>
                        <div class="form-group">
                            <label for="koordinat_y">Koordinat Y</label>
                            <input type="number" name="koordinat_y" class="form-control" step="any" required>
                        </div>
                        <div class="form-group">
                            <label for="radius">Radius</label>
                            <input type="number" name="radius" class="form-control" required>
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
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
