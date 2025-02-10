@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Payroll List</h1>

    <a href="{{ route('payroll.create') }}" class="btn btn-primary mb-3">Create New Payroll</a>

    @if($payrolls->count())
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Employee Name</th>
                        <th>Payroll Date</th>
                        <th>Take Home Pay (IDR)</th>
                        <th>Reviewed Status</th>
                        <th>Paid Status</th>
                        <th>Actions</th>
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
                                    <span class="badge bg-success">Reviewed by {{ $payroll->reviewer->nama }}</span>
                                @else
                                    <span class="badge bg-secondary">Not Reviewed</span>
                                @endif
                            </td>

                            <!-- Paid Status -->
                            <td>
                                @if($payroll->status_pembayaran)
                                    <span class="badge bg-success">Paid</span>
                                @else
                                    <span class="badge bg-danger">Unpaid</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td>
                                @if(!$payroll->is_reviewed)
                                    <a href="{{ route('payroll.review', $payroll->id) }}" class="btn btn-info btn-sm">Review</a>
                                    <a href="{{ route('payroll.edit', $payroll->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    
                                    <!-- Delete button -->
                                    <form action="{{ route('payroll.destroy', $payroll->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Apakah anda yakin untuk menghapus data payroll ini?')">Delete</button>
                                    </form>
                                @else
                                    <a href="{{ route('payroll.review', $payroll->id) }}" class="btn btn-info btn-sm">Detail</a>
                                    @if(!$payroll->status_pembayaran)
                                        <!-- Mark as paid button -->
                                        <form action="{{ route('payroll.markAsPaid', $payroll->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success btn-sm"
                                            onclick="return confirm('Apakah Anda yakin ingin menandai gaji berikut sebagai telah dibayar? Tindakan ini tidak dapat diubah.')">Tandai Sudah Dibayar</button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info">
            Belum ada data payroll. <a href="{{ route('payroll.create') }}" class="alert-link">Tambahkan data payroll baru.</a>.
        </div>
    @endif
@endsection
