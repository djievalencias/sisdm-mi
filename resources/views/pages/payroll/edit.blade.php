@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Edit Payroll</h1>

    <form method="POST" action="{{ route('payroll.update', $payroll->id) }}" id="payrollForm">
        @csrf
        @method('PUT')

        <!-- Payroll Information -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="id_user" class="form-label">User ID:</label>
                <input type="number" name="id_user" id="id_user" class="form-control" value="{{ $payroll->id_user }}" readonly>
            </div>

            <div class="col-md-4">
                <label for="tanggal_payroll" class="form-label">Payroll Date:</label>
                <input type="date" name="tanggal_payroll" id="tanggal_payroll" class="form-control" value="{{ $payroll->tanggal_payroll }}" required>
            </div>

            <div class="col-md-4">
                <label for="umk" class="form-label">UMK (Minimum Wage):</label>
                <input type="number" step="0.01" name="umk" id="umk" class="form-control" value="3454827" required>
            </div>
        </div>

        <!-- Calculated Pays -->
        <h3>Calculated Pays</h3>
        <div class="row">
            <div class="col-md-4">
                <label for="gaji_pokok" class="form-label">Base Salary (IDR):</label>
                <input type="text" name="gaji_pokok" id="gaji_pokok" class="form-control" value="{{ $payroll->gaji_pokok }}" readonly>
            </div>

            <div class="col-md-4">
                <label for="upah_lembur" class="form-label">Overtime Pay (IDR):</label>
                <input type="text" name="upah_lembur" id="upah_lembur" class="form-control" value="{{ $payroll->upah_lembur }}" readonly>
            </div>

            <div class="col-md-4">
                <label for="gaji_tgl_merah" class="form-label">Holiday Pay (IDR):</label>
                <input type="text" name="gaji_tgl_merah" id="gaji_tgl_merah" class="form-control" value="{{ $payroll->gaji_tgl_merah }}" readonly>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-4">
                <label for="upah_lembur_tgl_merah" class="form-label">Holiday Overtime Pay (IDR):</label>
                <input type="text" name="upah_lembur_tgl_merah" id="upah_lembur_tgl_merah" class="form-control" value="{{ $payroll->upah_lembur_tgl_merah }}" readonly>
            </div>

            <div class="col-md-4">
                <label for="iuran_bpjs_kantor" class="form-label">Company BPJS Contribution (IDR):</label>
                <input type="text" name="iuran_bpjs_kantor" id="iuran_bpjs_kantor" class="form-control" value="{{ $payroll->iuran_bpjs_kantor }}" readonly>
            </div>

            <div class="col-md-4">
                <label for="iuran_bpjs_karyawan" class="form-label">Employee BPJS Contribution (IDR):</label>
                <input type="text" name="iuran_bpjs_karyawan" id="iuran_bpjs_karyawan" class="form-control" value="{{ $payroll->iuran_bpjs_karyawan }}" readonly>
            </div>
        </div>

        <!-- Total Take Home Pay -->
        <div class="row mt-3">
            <div class="col-md-4">
                <label for="take_home_pay" class="form-label">Total Take Home Pay (IDR):</label>
                <input type="text" name="take_home_pay" id="take_home_pay" class="form-control" value="{{ $payroll->take_home_pay }}" readonly>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Update Payroll</button>
        </div>
    </form>

    <hr>

    <!-- Tunjangan Section -->
    <h3>Tunjangan (Allowances)</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Nominal (IDR)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tunjangan as $item)
                <tr>
                    <td>{{ $item->nama }}</td>
                    <td>{{ number_format($item->nominal, 2) }}</td>
                    <td>
                        <a href="{{ route('tunjangan.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('tunjangan.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Add New Tunjangan</h4>
    <form method="POST" action="{{ route('tunjangan.store', ['id_payroll' => $payroll->id]) }}">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6">
                <input type="text" name="nama" class="form-control" placeholder="Nama Tunjangan" required>
            </div>
            <div class="col-md-6">
                <input type="number" name="nominal" class="form-control" placeholder="Nominal (IDR)" required>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Add Tunjangan</button>
    </form>

    <hr>

    <!-- Potongan Section -->
    <h3>Potongan (Deductions)</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Nominal (IDR)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($potongan as $item)
                <tr>
                    <td>{{ $item->nama }}</td>
                    <td>{{ number_format($item->nominal, 2) }}</td>
                    <td>
                        <a href="{{ route('potongan.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('potongan.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Add New Potongan</h4>
    <form method="POST" action="{{ route('potongan.store', ['id_payroll' => $payroll->id]) }}">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6">
                <input type="text" name="nama" class="form-control" placeholder="Nama Potongan" required>
            </div>
            <div class="col-md-6">
                <input type="number" name="nominal" class="form-control" placeholder="Nominal (IDR)" required>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Add Potongan</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Recalculate take-home pay dynamically
        function calculateTakeHomePay(baseTakeHomePay) {
            let totalTunjangan = 0;
            let totalPotongan = 0;

            // Sum tunjangan
            $('table tbody tr').each(function () {
                const tunjanganValue = parseFloat($(this).find('td:nth-child(2)').text().replace(/,/g, '')) || 0;
                totalTunjangan += tunjanganValue;
            });

            // Sum potongan
            $('table tbody tr').each(function () {
                const potonganValue = parseFloat($(this).find('td:nth-child(2)').text().replace(/,/g, '')) || 0;
                totalPotongan += potonganValue;
            });

            // Calculate final take-home pay
            const finalTakeHomePay = baseTakeHomePay + totalTunjangan - totalPotongan;
            $('#take_home_pay').val(finalTakeHomePay.toFixed(2));
        }

        // On page load
        let baseTakeHomePay = parseFloat($('#take_home_pay').val()) || 0;
        calculateTakeHomePay(baseTakeHomePay);
    });
</script>
@endsection
