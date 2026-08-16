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
                    <table class="table si-datatable">
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
                        <tbody>
                            @foreach ($payrolls as $payroll)
                                <tr>
                                    <td>{{ $payroll->user->nama }}</td>
                                    <td>{{ \Carbon\Carbon::parse($payroll->tanggal_payroll)->format('d M Y') }}</td>
                                    <td>{{ number_format($payroll->take_home_pay, 2) }}</td>

                                    <!-- Reviewed Status -->
                                    <td>
                                        @if($payroll->is_reviewed)
                                            <span class="si-pill green">{{ __('Reviewed by') }} {{ $payroll->reviewer->nama }}</span>
                                        @else
                                            <span class="si-pill grey">{{ __('Not Reviewed') }}</span>
                                        @endif
                                    </td>

                                    <!-- Paid Status -->
                                    <td>
                                        @if($payroll->status_pembayaran)
                                            <span class="si-pill green">{{ __('Paid') }}</span>
                                        @else
                                            <span class="si-pill red">{{ __('Unpaid') }}</span>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td>
                                        @if(!$payroll->is_reviewed)
                                            <a href="{{ route('payroll.review', $payroll->id) }}" class="btn btn-info btn-sm">{{ __('Review') }}</a>
                                            <a href="{{ route('payroll.edit', $payroll->id) }}" class="btn btn-warning btn-sm">{{ __('Edit') }}</a>

                                            <!-- Delete button -->
                                            <form action="{{ route('payroll.destroy', $payroll->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('{{ __('Are you sure you want to delete this data?') }}')">{{ __('Delete') }}</button>
                                            </form>
                                        @else
                                            <a href="{{ route('payroll.review', $payroll->id) }}" class="btn btn-info btn-sm">{{ __('Detail') }}</a>
                                            <a href="{{ route('payroll.slip', $payroll->id) }}" class="btn btn-secondary btn-sm">{{ __('Payslip') }}</a>
                                            @if(!$payroll->status_pembayaran)
                                                <!-- Mark as paid button -->
                                                <form action="{{ route('payroll.markAsPaid', $payroll->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-success btn-sm"
                                                        onclick="return confirm('{{ __('Are you sure you want to mark this salary as paid? This action cannot be undone.') }}')">{{ __('Mark as Paid') }}</button>
                                                </form>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </x-page>
@endsection
