@extends('layouts.app')

@section('content')
    <x-page :title="__('Attendance')">

        @include('layouts._toolbar', [
            'create_url' => route('attendance.create'),
            'create_label' => __('Add Attendance'),
            'filter_action' => route('attendance.index'),
            'filters' => [
                ['type' => 'select', 'name' => 'id_user', 'label' => __('Employee'), 'options' => $users->pluck('nama', 'id')],
                ['type' => 'date', 'name' => 'from', 'label' => __('From')],
                ['type' => 'date', 'name' => 'to', 'label' => __('To')],
                ['type' => 'select', 'name' => 'status', 'label' => __('Clocked out?'), 'options' => ['0' => __('Not yet'), '1' => __('Checked out')]],
                ['type' => 'select', 'name' => 'is_tanggal_merah', 'label' => __('Holiday?'), 'options' => ['0' => __('No'), '1' => __('Yes')]],
            ],
        ])

        <div class="card">
            <div class="card-body">
                <table class="table si-datatable">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Employee') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Clocked out?') }}</th>
                            <th>{{ __('Workdays') }}</th>
                            <th>{{ __('Overtime Hours') }}</th>
                            <th>{{ __('Holiday?') }}</th>
                            <th class="no-sort">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->id }}</td>
                                <td>{{ $attendance->user->nama ?? __('No User') }}</td>
                                <td>{{ $attendance->tanggal->format('Y-m-d H:i:s') }}</td>
                                <td>{{ $attendance->status ? __('Checked out') : __('Not yet') }}</td>
                                <td>{{ $attendance->hari_kerja }}</td>
                                <td>{{ $attendance->jumlah_jam_lembur }}</td>
                                <td>{{ $attendance->is_tanggal_merah ? __('Yes') : __('No') }}</td>
                                <td>
                                    <a href="{{ route('attendance.show', $attendance->id) }}" class="btn btn-sm btn-info">{{ __('Detail') }}</a>
                                    <a href="{{ route('attendance.edit', $attendance->id) }}" class="btn btn-sm btn-warning">{{ __('Edit') }}</a>
                                    <form action="{{ route('attendance.destroy', $attendance->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('{{ __('Are you sure you want to delete this data?') }}')">{{ __('Delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </x-page>
@endsection
