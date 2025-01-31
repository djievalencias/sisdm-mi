@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Event Calendar</h2>
    <a href="{{ route('kalender.create') }}" class="btn btn-primary mb-3">Create New Event</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($events as $event)
            <tr>
                <td>{{ $event->judul }}</td>
                <td>{{ $event->tanggal_mulai }}</td>
                <td>{{ $event->tanggal_selesai ?? '-' }}</td>
                <td>{{ ucfirst($event->tipe) }}</td>
                <td>
                    <a href="{{ route('kalender.show', $event->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('kalender.edit', $event->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('kalender.destroy', $event->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
