@extends('layouts.app')

@section('content')
    <x-page :title="__('Payroll')">

        @include('layouts._toolbar', [
            'create_url' => route('payroll.create'),
            'create_label' => __('Add Payroll'),
            'filter_action' => route('payroll.index'),
            'filters' => [
                ['type' => 'select', 'name' => 'id_user', 'label' => __('Employee'), 'options' => $users->pluck('nama', 'id')],
                ['type' => 'month', 'name' => 'month', 'label' => __('Month')],
                ['type' => 'select', 'name' => 'is_reviewed', 'label' => __('Reviewed'), 'options' => ['0' => __('Not Reviewed'), '1' => __('Reviewed')]],
                ['type' => 'select', 'name' => 'status_pembayaran', 'label' => __('Paid'), 'options' => ['0' => __('Unpaid'), '1' => __('Paid')]],
            ],
        ])

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="payroll-table">
                        <thead>
                            <tr>
                                <th>{{ __('Employee') }}</th>
                                <th>{{ __('Payroll Date') }}</th>
                                <th>{{ __('Take Home Pay (IDR)') }}</th>
                                <th>{{ __('Reviewed') }}</th>
                                <th>{{ __('Paid') }}</th>
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
            $('#payroll-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                language: window.SISDM.dtLang,
                lengthMenu: [10, 25, 50, 100],
                order: [[1, 'desc']],
                ajax: '{{ route('payroll.index', request()->query()) }}',
                columns: [
                    { data: 'user_nama', name: 'user_nama' },
                    { data: 'tanggal_payroll', name: 'payroll.tanggal_payroll' },
                    { data: 'take_home_pay', name: 'payroll.take_home_pay' },
                    { data: 'reviewed', name: 'payroll.is_reviewed', searchable: false },
                    { data: 'paid', name: 'payroll.status_pembayaran', searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
            });
        });
    </script>
@endpush
