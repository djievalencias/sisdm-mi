@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Event</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('kalender.update', $kalender->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="judul" class="form-label">Event Title</label>
            <input type="text" name="judul" class="form-control" value="{{ old('judul', $kalender->judul) }}" required>
        </div>

        <div class="mb-3">
            <label for="tanggal_mulai" class="form-label">Start Date</label>
            <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai', $kalender->tanggal_mulai) }}" required>
        </div>

        <div class="mb-3">
            <label for="tanggal_selesai" class="form-label">End Date (Optional)</label>
            <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai', $kalender->tanggal_selesai) }}">
        </div>

        <div class="mb-3">
            <label for="tipe" class="form-label">Event Type</label>
            <select name="tipe" class="form-control" required>
                <option value="hari_libur" {{ old('tipe', $kalender->tipe) == 'hari_libur' ? 'selected' : '' }}>Holiday</option>
                <option value="meeting" {{ old('tipe', $kalender->tipe) == 'meeting' ? 'selected' : '' }}>Meeting</option>
                <option value="acara" {{ old('tipe', $kalender->tipe) == 'acara' ? 'selected' : '' }}>Event</option>
                <option value="lainnya" {{ old('tipe', $kalender->tipe) == 'lainnya' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <div class="form-group">
            <label for="repeat_type">Repeat</label>
            <select name="repeat_type" id="repeat_type" class="form-control">
                <option value="never" {{ old('repeat_type', $kalender->repeat_type ?? 'never') == 'never' ? 'selected' : '' }}>Never</option>
                <option value="weekly" {{ old('repeat_type', $kalender->repeat_type ?? '') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                <option value="monthly" {{ old('repeat_type', $kalender->repeat_type ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="yearly" {{ old('repeat_type', $kalender->repeat_type ?? '') == 'yearly' ? 'selected' : '' }}>Yearly</option>
            </select>
        </div>

        <div class="form-group">
            <label for="repeat_until">Repeat Until</label>
            <input type="date" name="repeat_until" id="repeat_until" class="form-control" value="{{ old('repeat_until', $kalender->repeat_until ?? '') }}">
        </div>

        <button type="submit" class="btn btn-success">Update Event</button>
        <a href="{{ route('kalender.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
