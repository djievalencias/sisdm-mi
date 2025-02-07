{{-- resources/views/payroll/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detail Payroll #{{ $payroll->id }}</h1>

    <div class="mb-3">
        <strong>User: </strong> {{ $payroll->user->name ?? '-' }}
    </div>
    <div class="mb-3">
        <strong>Tanggal Payroll: </strong> {{ $payroll->tanggal_payroll }}
    </div>
    <div class="mb-3">
        <strong>Gaji Pokok: </strong> {{ $payroll->gaji_pokok }}
    </div>
    <div class="mb-3">
        <strong>Upah Lembur: </strong> {{ $payroll->upah_lembur }}
    </div>
    <div class="mb-3">
        <strong>Gaji Tgl Merah: </strong> {{ $payroll->gaji_tgl_merah }}
    </div>
    <div class="mb-3">
        <strong>Upah Lembur Tgl Merah: </strong> {{ $payroll->upah_lembur_tgl_merah }}
    </div>
    <div class="mb-3">
        <strong>BPJS Kantor: </strong> {{ $payroll->iuran_bpjs_kantor }}
    </div>
    <div class="mb-3">
        <strong>BPJS Karyawan: </strong> {{ $payroll->iuran_bpjs_karyawan }}
    </div>
    <div class="mb-3">
        <strong>Is Reviewed? </strong> {{ $payroll->is_reviewed ? 'Yes' : 'No' }}
    </div>
    <div class="mb-3">
        <strong>Status (Paid?): </strong> {{ $payroll->status ? 'Sudah Bayar' : 'Belum Bayar' }}
    </div>

    <a href="{{ route('payroll.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
