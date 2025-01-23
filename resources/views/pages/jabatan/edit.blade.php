@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1>Edit Jabatan</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <a href="{{ route('jabatan.index') }}" class="btn btn-secondary mb-3">Back</a>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('jabatan.update', $jabatan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" class="form-control" value="{{ $jabatan->nama }}" required>
                    </div>
                    <div class="form-group">
                        <label for="id_grup">Grup</label>
                        <select name="id_grup" class="form-control">
                            <option value="">Select Grup</option>
                            @foreach ($grup as $g)
                            <option value="{{ $g->id }}" {{ $jabatan->id_grup == $g->id ? 'selected' : '' }}>
                                {{ $g->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" class="form-control">{{ $jabatan->description }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
