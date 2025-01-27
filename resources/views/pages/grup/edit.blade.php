@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1>Edit Grup</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <a href="{{ route('grup.index') }}" class="btn btn-secondary mb-3">Back</a>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('grup.update', $grup->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" class="form-control" value="{{ $grup->nama }}" required>
                    </div>
                    <div class="form-group">
                        <label for="id_departemen">Departemen</label>
                        <select name="id_departemen" class="form-control">
                            <option value="">Select Departemen</option>
                            @foreach ($departemen as $d)
                            <option value="{{ $d->id }}" {{ $grup->id_departemen == $d->id ? 'selected' : '' }}>
                                {{ $d->nama }}
                            </option>
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
