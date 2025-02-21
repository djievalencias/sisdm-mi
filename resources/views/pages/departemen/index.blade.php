@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1>Departemen</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <a href="{{ route('user.index') }}" class="btn btn-sm btn-primary mb-2">Back</a>
        <a href="{{ route('departemen.create') }}" class="btn btn-sm btn-primary mb-2">Add Departemen</a>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">List of Departemen</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Kantor</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departemen as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->kantor->nama ?? '-' }}</td>
                            <td>
                                <a href="{{ route('departemen.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('departemen.destroy', $item->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this Departemen?')">Delete</button>
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
