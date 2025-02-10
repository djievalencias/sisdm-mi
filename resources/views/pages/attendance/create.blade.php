@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Buat Attendance Baru</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('status'))
        <div class="alert alert-info">{{ session('status') }}</div>
    @endif

    <form action="{{ route('attendance.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="type" class="form-label">Type (in/out/lembur)</label>
            <select name="type" id="type" class="form-control">
                <option value="in" {{ old('type')=='in' ? 'selected' : '' }}>IN (Check-in)</option>
                <option value="out" {{ old('type')=='out' ? 'selected' : '' }}>OUT (Check-out)</option>
            </select>
            @error('type') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="long" class="form-label">Longitude</label>
            <input type="text" name="long" class="form-control" value="{{ old('long') }}">
            @error('long') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="lat" class="form-label">Latitude</label>
            <input type="text" name="lat" class="form-control" value="{{ old('lat') }}">
            @error('lat') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" name="address" class="form-control" value="{{ old('address') }}">
            @error('address') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="photo" class="form-label">Photo (image)</label>
            <input type="file" name="photo" class="form-control">
            @error('photo') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
