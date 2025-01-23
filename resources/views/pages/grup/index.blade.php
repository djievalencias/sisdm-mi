@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1>Grup</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <a href="{{ route('user.index') }}" class="btn btn-sm btn-primary mb-2">Back</a>
        <a href="{{ route('grup.create') }}" class="btn btn-sm btn-primary mb-2">Add Grup</a>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">List of Grup</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Departemen</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($grup as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->departemen->nama ?? '-' }}</td>
                            <td>
                                <a href="{{ route('grup.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('grup.destroy', $item->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this Grup?')">Delete</button>
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
