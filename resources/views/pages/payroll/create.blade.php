@extends('layouts.app')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container mt-5">
    <h1>Create Payroll</h1>

    <form method="POST" action="{{ route('payroll.store') }}" id="payrollForm">
        @csrf

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="id_user" class="form-label">User ID:</label>
                <input type="number" name="id_user" id="id_user" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label for="tanggal_payroll" class="form-label">Payroll Date:</label>
                <input type="date" name="tanggal_payroll" id="tanggal_payroll" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label for="umk" class="form-label">UMK (Minimum Wage):</label>
                <input type="number" step="0.01" name="umk" id="umk" class="form-control" value="3454827" required>
            </div>
        </div>

        <h3>Calculated Pays</h3>

        <div class="row">
            <div class="col-md-4">
                <label for="total_hari_kerja" class="form-label">Total Work Days:</label>
                <input type="text" id="total_hari_kerja" name="total_hari_kerja" class="form-control" readonly>
            </div>

            <div class="col-md-4">
                <label for="gaji_pokok" class="form-label">Base Salary (IDR):</label>
                <input type="text" name="gaji_pokok" id="gaji_pokok" class="form-control" readonly>
            </div>

            <div class="col-md-4">
                <label for="upah_lembur" class="form-label">Overtime Pay (IDR):</label>
                <input type="text" name="upah_lembur" id="upah_lembur" class="form-control" readonly>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-4">
                <label for="gaji_tgl_merah" class="form-label">Holiday Pay (IDR):</label>
                <input type="text" name="gaji_tgl_merah" id="gaji_tgl_merah" class="form-control" readonly>
            </div>

            <div class="col-md-4">
                <label for="upah_lembur_tgl_merah" class="form-label">Holiday Overtime Pay (IDR):</label>
                <input type="text" name="upah_lembur_tgl_merah" id="upah_lembur_tgl_merah" class="form-control" readonly>
            </div>

            <div class="col-md-4">
                <label for="iuran_bpjs_kantor" class="form-label">Company BPJS Contribution (IDR):</label>
                <input type="text" name="iuran_bpjs_kantor" id="iuran_bpjs_kantor" class="form-control" readonly>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-4">
                <label for="iuran_bpjs_karyawan" class="form-label">Employee BPJS Contribution (IDR):</label>
                <input type="text" name="iuran_bpjs_karyawan" id="iuran_bpjs_karyawan" class="form-control" readonly>
            </div>

            <div class="col-md-4">
                <label for="take_home_pay" class="form-label">Total Take Home Pay (IDR):</label>
                <input type="text" name="take_home_pay" id="take_home_pay" class="form-control" readonly>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#id_user, #tanggal_payroll, #umk').on('change', function () {
        const id_user = $('#id_user').val();
        const tanggal_payroll = $('#tanggal_payroll').val();
        const umk = $('#umk').val();

        if (id_user && tanggal_payroll && umk) {
            $.ajax({
                url: "{{ route('payroll.calculate') }}",
                method: 'GET',
                data: {
                    id_user: id_user,
                    tanggal_payroll: tanggal_payroll,
                    umk: umk
                },
                success: function (response) {
                    $('#total_hari_kerja').val(response.total_hari_kerja + " days");
                    $('#gaji_pokok').val(response.gaji_pokok.toFixed(2));
                    $('#upah_lembur').val(response.upah_lembur.toFixed(2));
                    $('#gaji_tgl_merah').val(response.gaji_tgl_merah.toFixed(2));
                    $('#upah_lembur_tgl_merah').val(response.upah_lembur_tgl_merah.toFixed(2));
                    $('#iuran_bpjs_kantor').val(response.iuran_bpjs_kantor.toFixed(2));
                    $('#iuran_bpjs_karyawan').val(response.iuran_bpjs_karyawan.toFixed(2));
                    $('#take_home_pay').val(response.take_home_pay.toFixed(2));
                },
                error: function (xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        }
    });
</script>
@endsection
