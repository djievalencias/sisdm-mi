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
                <div class="table-responsive">
                    <table class="table" id="attendance-table">
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
            $('#attendance-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                language: window.SISDM.dtLang,
                lengthMenu: [10, 25, 50, 100],
                order: [[2, 'desc']],
                // Current filter values ride along so the ajax result matches the toolbar.
                ajax: '{{ route('attendance.index', request()->query()) }}',
                columns: [
                    { data: 'id', name: 'attendances.id' },
                    { data: 'user_nama', name: 'user_nama' },
                    { data: 'tanggal', name: 'attendances.tanggal' },
                    { data: 'status', name: 'attendances.status' },
                    { data: 'hari_kerja', name: 'attendances.hari_kerja' },
                    { data: 'jumlah_jam_lembur', name: 'attendances.jumlah_jam_lembur' },
                    { data: 'is_tanggal_merah', name: 'attendances.is_tanggal_merah' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
            });
        });
    </script>
@endpush
