<!-- resources/views/shifts/create.blade.php -->

@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Create Shift</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('shift.index') }}">Shifts</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create Shift</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('shift.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="nama">Shift Name</label>
                                <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="waktu_mulai">Start Time</label>
                                <input type="time" name="waktu_mulai" id="waktu_mulai" class="form-control" value="{{ old('waktu_mulai') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="waktu_selesai">End Time</label>
                                <input type="time" name="waktu_selesai" id="waktu_selesai" class="form-control" value="{{ old('waktu_selesai') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="days">Days</label>
                                <div class="form-check">
                                    <input type="checkbox" name="senin" id="senin" value="1" class="form-check-input" {{ old('senin') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="senin">Monday</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="selasa" id="selasa" value="1" class="form-check-input" {{ old('selasa') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="selasa">Tuesday</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="rabu" id="rabu" value="1" class="form-check-input" {{ old('rabu') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rabu">Wednesday</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="kamis" id="kamis" value="1" class="form-check-input" {{ old('kamis') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="kamis">Thursday</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="jumat" id="jumat" value="1" class="form-check-input" {{ old('jumat') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="jumat">Friday</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="sabtu" id="sabtu" value="1" class="form-check-input" {{ old('sabtu') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sabtu">Saturday</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="minggu" id="minggu" value="1" class="form-check-input" {{ old('minggu') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="minggu">Sunday</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_mulai">Start Date</label>
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai') }}">
                            </div>
                            <div class="form-group">
                                <label for="tanggal_berakhir">End Date</label>
                                <input type="date" name="tanggal_berakhir" id="tanggal_berakhir" class="form-control" value="{{ old('tanggal_berakhir') }}">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                            </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Create Shift</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
