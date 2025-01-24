@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">User</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">User</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-12">

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Attendance Chart -->
                    <div class="mb-3">
                        <a href="{{ route('user.create') }}" class="btn btn-sm btn-primary">Add User</a>
                        <a href="{{ route('kantor.index') }}" class="btn btn-sm btn-info">Kantor</a>
                        <a href="{{ route('departemen.index') }}" class="btn btn-sm btn-info">Departemen</a>
                        <a href="{{ route('grup.index') }}" class="btn btn-sm btn-info">Grup</a>
                        <a href="{{ route('jabatan.index') }}" class="btn btn-sm btn-info">Jabatan</a>
                        <a href="{{ route('user.archived') }}" class="btn btn-sm btn-info">Archive</a>
                    </div>


                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="ion ion-clipboard mr-1"></i>
                                User
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <table class="table" id="datatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>E-Mail</th>
                                        <th>Jabatan</th>
                                        <th>Grup</th>
                                        <th>Departemen</th>
                                        <th>Kantor</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->nama }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->jabatan->nama ?? '-' }}</td>
                                            <td>{{ $user->jabatan->grup->nama ?? '-' }}</td>
                                            <td>{{ $user->jabatan->grup->departemen->nama ?? '-' }}</td>
                                            <td>{{ $user->jabatan->grup->departemen->kantor->nama ?? '-' }}</td>
                                            <td>
                                                <a href="{{ route('user.show', $user->id) }}"
                                                    class="btn btn-sm btn-secondary">Show</a>
                                                <a href="{{ route('user.edit', $user->id) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('user.archive', $user->id) }}" method="POST"
                                                    style="display:inline-block;">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-info">Archive</button>
                                                </form>
                                                <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                                                    style="display:inline-block;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>


                        </div>
                    </div>
                    <!-- /.card -->
                </section>
                <!-- /.Left col -->
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('user') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'nama',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush
