@extends('layouts.app')

@section('content')
    <x-page :title="__('Leave Requests')">

        @include('layouts._toolbar', [
            'actions' => [['url' => route('cuti-perizinan.hasil'), 'label' => __('View Processed Requests')]],
            'create_url' => auth()->user()->hasRole('admin') ? route('cuti-perizinan.create') : null,
            'create_label' => __('Add Leave Request'),
            'filter_action' => route('cuti-perizinan.index'),
            'filters' => [
                ['type' => 'select', 'name' => 'status', 'label' => __('Status'), 'options' => ['diajukan' => __('Submitted'), 'disetujui' => __('Approved'), 'ditolak' => __('Rejected')]],
                ['type' => 'select', 'name' => 'jenis', 'label' => __('Type'), 'options' => ['izin' => __('Permission'), 'alpa' => __('Absence'), 'sakit' => __('Sick')]],
                ['type' => 'date', 'name' => 'from', 'label' => __('From')],
                ['type' => 'date', 'name' => 'to', 'label' => __('To')],
            ],
        ])

        <div class="card">
            <div class="card-body">
                <table class="table si-datatable">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Start Date') }}</th>
                            <th>{{ __('End Date') }}</th>
                            <th>{{ __('Notes') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="no-sort">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cutiPerizinans as $izin)
                            <tr>
                                <td>{{ $izin->user->nama }}</td>
                                <td>{{ $izin->tanggal_mulai }}</td>
                                <td>{{ $izin->tanggal_selesai }}</td>
                                <td>{{ $izin->keterangan }}</td>
                                <td>{{ __(ucfirst($izin->jenis)) }}</td>
                                <td>
                                    <span class="si-pill {{ $izin->status_pengajuan == 'diajukan' ? 'amber' : ($izin->status_pengajuan == 'disetujui' ? 'green' : 'red') }}">
                                        {{ __(ucfirst($izin->status_pengajuan)) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('cuti-perizinan.show', $izin->id) }}" class="btn btn-info btn-sm">{{ __('Detail') }}</a>
                                    @role('admin')
                                        <a href="{{ route('cuti-perizinan.edit', $izin->id) }}" class="btn btn-warning btn-sm">{{ __('Edit') }}</a>
                                        <form action="{{ route('cuti-perizinan.destroy', $izin->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('{{ __('Are you sure you want to delete this data?') }}')">{{ __('Delete') }}</button>
                                        </form>
                                    @endrole
                                    @can('approve', $izin)
                                        <form action="{{ route('cuti-perizinan.approve', $izin->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">{{ __('Approve') }}</button>
                                        </form>
                                        <form action="{{ route('cuti-perizinan.reject', $izin->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary btn-sm">{{ __('Reject') }}</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </x-page>
@endsection
