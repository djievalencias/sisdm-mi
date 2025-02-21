@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Assign Users to Shift: {{ $shift->nama }}</h1>

    <form action="{{ route('shift.assign', $shift->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="users">Select Users</label>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Name</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    <input type="checkbox" name="users[]" value="{{ $user->id }}" 
                                        {{ in_array($user->id, $assignedUsers) ? 'checked' : '' }}>
                                </td>
                                <td>{{ $user->nama }}</td>
                                <td>{{ $user->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Assign Users</button>
        <a href="{{ route('shift.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
