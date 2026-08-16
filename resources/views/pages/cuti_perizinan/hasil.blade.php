@extends('layouts.app')

@section('content')
    <x-page :title="__('Processed Leave Requests')">

        @include('layouts._toolbar', [
            'actions' => [['url' => route('cuti-perizinan.index'), 'label' => __('Back'), 'class' => 'btn-secondary']],
            'filter_action' => route('cuti-perizinan.hasil'),
            'filters' => [
                ['type' => 'select', 'name' => 'status', 'label' => __('Status'), 'options' => ['disetujui' => __('Approved'), 'ditolak' => __('Rejected')]],
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
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Approved By') }}</th>
                            <th class="no-sort">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cutiPerizinans as $izin)
                            <tr>
                                <td>{{ $izin->user->nama }}</td>
                                <td>{{ $izin->tanggal_mulai }}</td>
                                <td>{{ $izin->tanggal_selesai }}</td>
                                <td>{{ $izin->keterangan }}</td>
                                <td>
                                    <span class="si-pill {{ $izin->status_pengajuan == 'disetujui' ? 'green' : 'red' }}">
                                        {{ __(ucfirst($izin->status_pengajuan)) }}
                                    </span>
                                </td>
                                <td>{{ $izin->disetujuiOleh->nama ?? 'Admin' }}</td>
                                <td>
                                    @can('undo', $izin)
                                        <form action="{{ route('cuti-perizinan.undo', $izin->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm">{{ __('Undo') }}</button>
                                        </form>
                                    @else
                                        -
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-muted">{{ __('No processed requests yet.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </x-page>
@endsection
