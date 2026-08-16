@extends('layouts.app')

@section('content')
    <x-page :title="__('Review Payroll')">

        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">{{ __('Employee Information') }}</h3>
            </div>
            <div class="card-body">
                <p><strong>{{ __('Name') }}:</strong> {{ $payroll->user->nama }}</p>
                <p><strong>{{ __('Payroll Date:') }}</strong> {{ $payroll->tanggal_payroll }}</p>
                <p><strong>{{ __('Take Home Pay:') }}</strong> IDR {{ number_format($payroll->take_home_pay, 2) }}</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">{{ __('Review and Payment Status') }}</h3>
            </div>
            <div class="card-body">
                <p><strong>{{ __('Reviewed:') }}</strong>
                    @if($payroll->is_reviewed)
                        <span class="badge badge-success">{{ __('Reviewed') }}</span> {{ __('by') }} {{ $payroll->reviewer->nama }} — {{ \Carbon\Carbon::parse($payroll->reviewed_at)->format('d M Y, H:i') }}
                    @else
                        <span class="badge badge-secondary">{{ __('Not Reviewed') }}</span>
                    @endif
                </p>

                <p><strong>{{ __('Payment:') }}</strong>
                    @if($payroll->status_pembayaran)
                        <span class="badge badge-success">{{ __('Paid') }}</span> — {{ \Carbon\Carbon::parse($payroll->dibayar_at)->format('d M Y, H:i') }}
                    @else
                        <span class="badge badge-danger">{{ __('Unpaid') }}</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">{{ __('Payroll Details') }}</h3>
            </div>
            <div class="card-body">
                <p><strong>{{ __('Base Salary:') }}</strong> IDR {{ number_format($payroll->gaji_pokok, 2) }}</p>
                <p><strong>{{ __('Overtime Pay:') }}</strong> IDR {{ number_format($payroll->upah_lembur, 2) }}</p>
                <p><strong>{{ __('Holiday Pay:') }}</strong> IDR {{ number_format($payroll->gaji_tgl_merah, 2) }}</p>
                <p><strong>{{ __('Holiday Overtime Pay:') }}</strong> IDR {{ number_format($payroll->upah_lembur_tgl_merah, 2) }}</p>
                <p><strong>{{ __('Company BPJS:') }}</strong> IDR {{ number_format($payroll->iuran_bpjs_kantor, 2) }}</p>
                <p><strong>{{ __('Employee BPJS:') }}</strong> IDR {{ number_format($payroll->iuran_bpjs_karyawan, 2) }}</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">{{ __('Allowances') }}</h3>
            </div>
            <div class="card-body">
                @if ($payroll->tunjangan->count())
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Amount (IDR)') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payroll->tunjangan as $tunjangan)
                                <tr>
                                    <td>{{ $tunjangan->nama }}</td>
                                    <td>{{ number_format($tunjangan->nominal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>{{ __('No allowances added.') }}</p>
                @endif
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">{{ __('Deductions') }}</h3>
            </div>
            <div class="card-body">
                @if ($payroll->potongan->count())
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Amount (IDR)') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payroll->potongan as $potongan)
                                <tr>
                                    <td>{{ $potongan->nama }}</td>
                                    <td>{{ number_format($potongan->nominal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>{{ __('No deductions added.') }}</p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-body d-flex align-items-center flex-wrap" style="gap: .5rem;">
                <a href="{{ route('payroll.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> {{ __('Back') }}</a>
                @if (!$payroll->is_reviewed)
                    <a href="{{ route('payroll.edit', $payroll->id) }}" class="btn btn-warning">{{ __('Edit Payroll') }}</a>
                    <form action="{{ route('payroll.review.submit', $payroll->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('{{ __('Are you sure you have carefully reviewed all payroll details? This action is final and cannot be undone.') }}');">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success">{{ __('Review and Finalize') }}</button>
                    </form>
                @else
                    <a href="{{ route('payroll.slip', $payroll->id) }}" class="btn btn-info">{{ __('Payslip') }}</a>
                    @if (!$payroll->status_pembayaran)
                        <form action="{{ route('payroll.markAsPaid', $payroll->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('{{ __('Are you sure you want to mark this salary as paid? This action cannot be undone.') }}');">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success">{{ __('Mark as Paid') }}</button>
                        </form>
                    @else
                        <span class="text-muted ml-2">{{ __('Paid on') }} {{ optional($payroll->dibayar_at)->format('d M Y, H:i') }}</span>
                    @endif
                @endif
            </div>
        </div>

    </x-page>
@endsection
