@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <h1>Kantor</h1>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <a href="{{ route('user.index') }}" class="btn btn-sm btn-primary mb-2">Back</a>
            <a href="{{ route('kantor.create') }}" class="btn btn-sm btn-primary mb-2">Add Kantor</a>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List of Kantor</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Koordinat</th>
                                <th>Radius</th>
                                <th>Manager</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kantor as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->alamat }}</td>
                                    <td>{{ $item->koordinat_x }}, {{ $item->koordinat_y }}</td>
                                    <td>{{ $item->radius }}</td>
                                    <td>{{ $item->manager->nama ?? '-' }}</td> <!-- Display manager name -->
                                    <td>
                                        <a href="{{ route('kantor.edit', $item->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('kantor.destroy', $item->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete this Kantor?')">Delete</button>
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
