@extends('layouts.app')

@section('content')
    <x-page :title="__('Attendance Details') . ' #' . $attendance->id" :breadcrumb="__('Attendance')">
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <th>{{ __('Employee') }}</th>
                            <td>{{ $attendance->user->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Date') }}</th>
                            <td>{{ $attendance->tanggal->format('Y-m-d') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Clocked out?') }}</th>
                            <td>{{ $attendance->status ? __('Checked out') : __('Not yet') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Workdays') }}</th>
                            <td>{{ $attendance->hari_kerja }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Overtime Hours') }}</th>
                            <td>{{ $attendance->jumlah_jam_lembur ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Holiday?') }}</th>
                            <td>{{ $attendance->is_tanggal_merah ? __('Yes') : __('No') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Attendance Details:') }}</h3>
            </div>
            <div class="card-body">
                @if ($attendance->detail->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('Type (in/out)') }}</th>
                                    <th>{{ __('Longitude') }}</th>
                                    <th>{{ __('Latitude') }}</th>
                                    <th>{{ __('Address') }}</th>
                                    <th>{{ __('Timestamp') }}</th>
                                    <th>{{ __('Photo') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendance->detail as $dt)
                                    <tr>
                                        <td>{{ strtoupper($dt->type) }}</td>
                                        <td>{{ $dt->long }}</td>
                                        <td>{{ $dt->lat }}</td>
                                        <td>{{ $dt->address }}</td>
                                        <td>{{ \Carbon\Carbon::parse($dt->created_at)->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            @if ($dt->photo)
                                                <a href="{{ asset('storage/' . $dt->photo) }}" target="_blank">{{ __('View') }}</a>
                                            @else
                                                <span class="text-muted">{{ __('No photo available') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">{{ __('No details.') }}</p>
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('attendance.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> {{ __('Back') }}</a>
                <a href="{{ route('attendance.edit', $attendance->id) }}" class="btn btn-warning">{{ __('Edit') }}</a>
                <form action="{{ route('attendance.destroy', $attendance->id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('{{ __('Are you sure you want to delete this data?') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                </form>
            </div>
        </div>
    </x-page>
@endsection
