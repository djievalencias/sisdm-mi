@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Shifts</h3>
                    <a href="{{ route('shift.create') }}" class="btn btn-primary btn-sm float-right">Add Shift</a>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Active Days</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($shifts as $shift)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $shift->nama }}</td>
                                        <td>{{ $shift->waktu_mulai }}</td>
                                        <td>{{ $shift->waktu_selesai }}</td>
                                        <td>
                                            @php
                                                $days = [];
                                                if ($shift->senin) $days[] = 'Monday';
                                                if ($shift->selasa) $days[] = 'Tuesday';
                                                if ($shift->rabu) $days[] = 'Wednesday';
                                                if ($shift->kamis) $days[] = 'Thursday';
                                                if ($shift->jumat) $days[] = 'Friday';
                                                if ($shift->sabtu) $days[] = 'Saturday';
                                                if ($shift->minggu) $days[] = 'Sunday';
                                                echo implode(', ', $days) ?: 'None';
                                            @endphp
                                        </td>
                                        <td>{{ $shift->description ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('shift.edit', $shift->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('shift.destroy', $shift->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Are you sure you want to delete this shift?');">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No shifts available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
