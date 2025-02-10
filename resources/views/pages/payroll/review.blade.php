@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Review Payroll</h1>

        <div class="card mb-4">
            <div class="card-body">
                <h4>Employee Information</h4>
                <p><strong>Name:</strong> {{ $payroll->user->nama }}</p>
                <p><strong>Payroll Date:</strong> {{ $payroll->tanggal_payroll }}</p>
                <p><strong>Take Home Pay:</strong> IDR {{ number_format($payroll->take_home_pay, 2) }}</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h4>Review and Payment Status</h4>
                <p><strong>Reviewed Status:</strong> 
                    @if($payroll->is_reviewed)
                        <span class="badge bg-success">Reviewed</span> by {{ $payroll->reviewer->nama }} on {{ \Carbon\Carbon::parse($payroll->reviewed_at)->format('d M Y, H:i') }}
                    @else
                        <span class="badge bg-secondary">Not Reviewed</span>
                    @endif
                </p>

                <p><strong>Payment Status:</strong> 
                    @if($payroll->status_pembayaran)
                        <span class="badge bg-success">Paid</span> on {{ \Carbon\Carbon::parse($payroll->dibayar_at)->format('d M Y, H:i') }}
                    @else
                        <span class="badge bg-danger">Unpaid</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h4>Payroll Details</h4>
                <p><strong>Gaji Pokok:</strong> IDR {{ number_format($payroll->gaji_pokok, 2) }}</p>
                <p><strong>Upah Lembur:</strong> IDR {{ number_format($payroll->upah_lembur, 2) }}</p>
                <p><strong>Gaji Tanggal Merah:</strong> IDR {{ number_format($payroll->gaji_tgl_merah, 2) }}</p>
                <p><strong>Upah Lembur Tanggal Merah:</strong> IDR {{ number_format($payroll->upah_lembur_tgl_merah, 2) }}</p>
                <p><strong>BPJS Kantor:</strong> IDR {{ number_format($payroll->iuran_bpjs_kantor, 2) }}</p>
                <p><strong>BPJS Karyawan:</strong> IDR {{ number_format($payroll->iuran_bpjs_karyawan, 2) }}</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h4>Tunjangan</h4>
                @if ($payroll->tunjangan->count())
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Nominal (IDR)</th>
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
                    <p>No tunjangan added.</p>
                @endif
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h4>Potongan</h4>
                @if ($payroll->potongan->count())
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Nominal (IDR)</th>
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
                    <p>No potongan added.</p>
                @endif
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <!-- Review Form -->
            @if (!$payroll->is_reviewed)
                <a href="{{ route('payroll.edit', $payroll->id) }}" class="btn btn-warning">Edit Payroll</a>
                <form action="{{ route('payroll.review.submit', $payroll->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin telah memeriksa seluruh rincian payroll dengan teliti? Tindakan ini bersifat final dan tidak dapat diubah.');">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success">Review dan Finalisasi</button>
                </form>
            @else
                <span class="text-muted">Payroll sudah direview.</span>
            @endif
        </div>
    </div>
@endsection
