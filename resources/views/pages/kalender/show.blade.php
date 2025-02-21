@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Event Details</h2>

    <div class="card">
        <div class="card-header">
            <h4>{{ $kalender->judul }}</h4>
        </div>
        <div class="card-body">
            <table class="table">
                <tbody>
                    <tr>
                        <th>Start Date</th>
                        <td>{{ $kalender->tanggal_mulai }}</td>
                    </tr>
                    <tr>
                        <th>End Date</th>
                        <td>{{ $kalender->tanggal_selesai ?? 'Not specified' }}</td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td>{{ ucfirst(str_replace('_', ' ', $kalender->tipe)) }}</td>
                    </tr>
                    <tr>
                        <th>Created By</th>
                        <td>{{ $kalender->createdBy->nama ?? 'Unknown' }}</td>
                    </tr>
                    <tr>
                        <th>Last Updated By</th>
                        <td>{{ $kalender->updatedBy->nama ?? 'Unknown' }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $kalender->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Last Updated</th>
                        <td>{{ $kalender->updated_at->format('d M Y, H:i') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <a href="{{ route('kalender.index') }}" class="btn btn-secondary">Back</a>
            <a href="{{ route('kalender.edit', $kalender->id) }}" class="btn btn-warning">Edit</a>

            <form action="{{ route('kalender.destroy', $kalender->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this event?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection
