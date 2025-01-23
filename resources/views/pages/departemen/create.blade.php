@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1>Add Departemen</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <a href="{{ route('departemen.index') }}" class="btn btn-secondary mb-3">Back</a>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('departemen.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="id_kantor">Kantor</label>
                        <select name="id_kantor" class="form-control">
                            <option value="">Select Kantor</option>
                            @foreach ($kantor as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
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
