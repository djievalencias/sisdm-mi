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
                <div class="table-responsive">
                    {{-- serverSide table: rows come from the ajax endpoint, so the body stays empty --}}
                    <table class="table" id="leave-table">
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
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    </x-page>
@endsection

@push('scripts')
    <script>
        $(function () {
            $('#leave-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                language: window.SISDM.dtLang,
                lengthMenu: [10, 25, 50, 100],
                order: [[1, 'desc']],
                ajax: '{{ route('cuti-perizinan.index', request()->query()) }}',
                columns: [
                    { data: 'user_nama', name: 'user_nama' },
                    { data: 'tanggal_mulai', name: 'cuti_perizinan.tanggal_mulai' },
                    { data: 'tanggal_selesai', name: 'cuti_perizinan.tanggal_selesai' },
                    { data: 'keterangan', name: 'cuti_perizinan.keterangan' },
                    { data: 'jenis', name: 'cuti_perizinan.jenis' },
                    { data: 'status', name: 'cuti_perizinan.status_pengajuan', searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
            });
        });
    </script>
@endpush
