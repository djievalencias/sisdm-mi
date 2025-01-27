@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1>Jabatan</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <a href="{{ route('user.index') }}" class="btn btn-sm btn-primary mb-2">Back</a>
        <a href="{{ route('jabatan.create') }}" class="btn btn-sm btn-primary mb-2">Add Jabatan</a>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">List of Jabatan</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Grup</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jabatan as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->grup->nama ?? '-' }}</td>
                            <td>{{ $item->description ?? '-' }}</td>
                            <td>
                                <a href="{{ route('jabatan.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('jabatan.destroy', $item->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this Jabatan?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
