{{-- resources/views/payroll/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Payroll</h1>
    <a href="{{ route('payroll.create') }}" class="btn btn-primary">Buat Payroll Baru</a>

    @if (session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Tanggal Payroll</th>
                <th>Gaji Pokok</th>
                <th>Upah Lembur</th>
                <th>Gaji Tgl Merah</th>
                <th>Upah Lembur Tgl Merah</th>
                <th>Paid Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payrolls as $py)
                <tr>
                    <td>{{ $py->id }}</td>
                    <td>{{ $py->user->name ?? '-' }}</td>
                    <td>{{ $py->tanggal_payroll }}</td>
                    <td>{{ $py->gaji_pokok }}</td>
                    <td>{{ $py->upah_lembur }}</td>
                    <td>{{ $py->gaji_tgl_merah }}</td>
                    <td>{{ $py->upah_lembur_tgl_merah }}</td>
                    <td>{{ $py->status ? 'Sudah Bayar' : 'Belum Bayar' }}</td>
                    <td>
                        <a href="{{ route('payroll.show',$py->id) }}" class="btn btn-sm btn-info">Detail</a>
                        <a href="{{ route('payroll.edit',$py->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('payroll.destroy', $py->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Yakin hapus?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">Belum ada data payroll.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $payrolls->links() }}
</div>
@endsection
