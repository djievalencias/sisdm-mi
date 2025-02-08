{{-- resources/views/payroll/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detail Payroll #{{ $payroll->id }}</h1>

    <!-- Payroll Details -->
    <div class="mb-3">
        <strong>User: </strong> {{ $payroll->user->nama ?? '-' }}
    </div>
    <div class="mb-3">
        <strong>Tanggal Payroll: </strong> {{ $payroll->tanggal_payroll }}
    </div>
    <div class="mb-3">
        <strong>Gaji Pokok: </strong> {{ number_format($payroll->gaji_pokok, 2) }}
    </div>
    <div class="mb-3">
        <strong>Upah Lembur: </strong> {{ number_format($payroll->upah_lembur, 2) }}
    </div>
    <div class="mb-3">
        <strong>Gaji Tgl Merah: </strong> {{ number_format($payroll->gaji_tgl_merah, 2) }}
    </div>
    <div class="mb-3">
        <strong>Upah Lembur Tgl Merah: </strong> {{ number_format($payroll->upah_lembur_tgl_merah, 2) }}
    </div>
    <div class="mb-3">
        <strong>BPJS Kantor: </strong> {{ number_format($payroll->iuran_bpjs_kantor, 2) }}
    </div>
    <div class="mb-3">
        <strong>BPJS Karyawan: </strong> {{ number_format($payroll->iuran_bpjs_karyawan, 2) }}
    </div>
    <div class="mb-3">
        <strong>Take Home Pay: </strong> {{ number_format($payroll->take_home_pay, 2) }}
    </div>
    <div class="mb-3">
        <strong>Is Reviewed? </strong> {{ $payroll->is_reviewed ? 'Yes' : 'No' }}
    </div>
    <div class="mb-3">
        <strong>Status (Paid?): </strong> {{ $payroll->status ? 'Sudah Bayar' : 'Belum Bayar' }}
    </div>

    <hr>

    <!-- Tunjangan Section -->
    <h3>Tunjangan (Allowances)</h3>
    @if($tunjangan->isEmpty())
        <p>Tidak ada tunjangan untuk payroll ini.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Nominal (IDR)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tunjangan as $item)
                    <tr>
                        <td>{{ $item->nama }}</td>
                        <td>{{ number_format($item->nominal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <hr>

    <!-- Potongan Section -->
    <h3>Potongan (Deductions)</h3>
    @if($potongan->isEmpty())
        <p>Tidak ada potongan untuk payroll ini.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Nominal (IDR)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($potongan as $item)
                    <tr>
                        <td>{{ $item->nama }}</td>
                        <td>{{ number_format($item->nominal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('payroll.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
