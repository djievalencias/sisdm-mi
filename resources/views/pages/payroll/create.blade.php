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

<div class="container">
    <h1 class="mb-4">Buat Payroll</h1>

    <form method="POST" action="{{ route('payroll.store') }}" id="payrollForm">
        @csrf

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="id_user" class="form-label">ID Karyawan:</label>
                <input type="number" name="id_user" id="id_user" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label for="tanggal_payroll" class="form-label">Tanggal Payroll:</label>
                <input type="date" name="tanggal_payroll" id="tanggal_payroll" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label for="umk" class="form-label">UMK (Upah Minimum Kota):</label>
                <input type="number" step="0.01" name="umk" id="umk" class="form-control" value="3454827" required>
            </div>
        </div>

        <h3 class="mt-4">Perhitungan Gaji (Dengan Rincian Rumus)</h3>
        <div class="row">
            <div class="col-md-4">
                <label for="gaji_per_hari" class="form-label">Gaji Per Hari (IDR):</label>
                <input type="text" id="gaji_per_hari" class="form-control" readonly>
                <small class="text-muted">Rumus: UMK / 25</small>
            </div>
        </div>

        <hr>

        <h4>Gaji Pokok dan Lembur</h4>
        <div class="row">
            <div class="col-md-4">
                <label for="total_hari_kerja" class="form-label">Total Hari Kerja:</label>
                <input type="text" id="total_hari_kerja" name="total_hari_kerja" class="form-control" readonly>
                <small class="text-muted">Berdasarkan kehadiran karyawan dalam satu bulan</small>
            </div>

            <div class="col-md-4">
                <label for="gaji_pokok" class="form-label">Gaji Pokok (IDR):</label>
                <input type="text" name="gaji_pokok" id="gaji_pokok" class="form-control" readonly>
                <small class="text-muted">Rumus: Total Hari Kerja × Gaji Per Hari</small>
            </div>

            <div class="col-md-4">
                <label for="upah_lembur" class="form-label">Upah Lembur (IDR):</label>
                <input type="text" name="upah_lembur" id="upah_lembur" class="form-control" readonly>
                <small class="text-muted">Rumus: Total Jam Lembur × 1.5 × (Gaji Per Hari / 7)</small>
            </div>
        </div>

        <hr>

        <h4>Gaji dan Lembur pada Hari Libur</h4>
        <div class="row">
            <div class="col-md-6">
                <label for="gaji_tgl_merah" class="form-label">Gaji Tanggal Merah (IDR):</label>
                <input type="text" name="gaji_tgl_merah" id="gaji_tgl_merah" class="form-control" readonly>
                <small class="text-muted">Rumus: Hari Kerja di Tanggal Merah × 2 × Gaji Per Hari</small>
            </div>

            <div class="col-md-6">
                <label for="upah_lembur_tgl_merah" class="form-label">Upah Lembur Tanggal Merah (IDR):</label>
                <input type="text" name="upah_lembur_tgl_merah" id="upah_lembur_tgl_merah" class="form-control" readonly>
                <small class="text-muted">Rumus: Jam Lembur di Tanggal Merah × 2 × (Gaji Per Hari / 7)</small>
            </div>
        </div>

        <hr>

        <h4>Rincian BPJS</h4>
        <h5><strong>BPJS yang Dibayarkan Perusahaan</strong></h5>
        <div class="row">
            <div class="col-md-4">
                <label for="bpjs_kesehatan_perusahaan" class="form-label">BPJS Kesehatan (4% UMK):</label>
                <input type="text" id="bpjs_kesehatan_perusahaan" class="form-control" readonly>
            </div>
            <div class="col-md-4">
                <label for="bpjs_jkk" class="form-label">BPJS JKK (0.89% UMK):</label>
                <input type="text" id="bpjs_jkk" class="form-control" readonly>
            </div>
            <div class="col-md-4">
                <label for="bpjs_jht_perusahaan" class="form-label">BPJS JHT (3.7% UMK):</label>
                <input type="text" id="bpjs_jht_perusahaan" class="form-control" readonly>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-4">
                <label for="bpjs_jkm" class="form-label">BPJS JKM (0.3% UMK):</label>
                <input type="text" id="bpjs_jkm" class="form-control" readonly>
            </div>
            <div class="col-md-4">
                <label for="bpjs_jp_perusahaan" class="form-label">BPJS JP (2% UMK):</label>
                <input type="text" id="bpjs_jp_perusahaan" class="form-control" readonly>
            </div>
            <div class="col-md-4">
                <label for="iuran_bpjs_kantor" class="form-label">Total Iuran BPJS Perusahaan:</label>
                <input type="text" name="iuran_bpjs_kantor" id="iuran_bpjs_kantor" class="form-control" readonly>
            </div>
        </div>

        <h5 class="mt-4"><strong>BPJS yang Dibayarkan Karyawan</strong></h5>
        <div class="row">
            <div class="col-md-4">
                <label for="bpjs_kesehatan_karyawan" class="form-label">BPJS Kesehatan (1% UMK):</label>
                <input type="text" id="bpjs_kesehatan_karyawan" class="form-control" readonly>
            </div>
            <div class="col-md-4">
                <label for="bpjs_jht_karyawan" class="form-label">BPJS JHT (2% UMK):</label>
                <input type="text" id="bpjs_jht_karyawan" class="form-control" readonly>
            </div>
            <div class="col-md-4">
                <label for="bpjs_jp_karyawan" class="form-label">BPJS JP (1% UMK):</label>
                <input type="text" id="bpjs_jp_karyawan" class="form-control" readonly>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-4">
                <label for="iuran_bpjs_karyawan" class="form-label">Total Iuran BPJS Karyawan:</label>
                <input type="text" name="iuran_bpjs_karyawan" id="iuran_bpjs_karyawan" class="form-control" readonly>
            </div>
        </div>

        <hr>

        <h4>Total Take Home Pay</h4>
        <div class="row">
            <div class="col-md-8">
                <label for="take_home_pay" class="form-label">Total Take Home Pay (IDR):</label>
                <input type="text" name="take_home_pay" id="take_home_pay" class="form-control" readonly>
                <small class="text-muted">Rumus: Gaji Pokok + Upah Lembur + Gaji Tanggal Merah + Upah Lembur Tanggal Merah + BPJS Perusahaan - BPJS Karyawan</small>
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
                    const gajiPerHari = (umk / 25).toFixed(2);
                    $('#gaji_per_hari').val(gajiPerHari);

                    $('#total_hari_kerja').val(response.total_hari_kerja + " days");
                    $('#gaji_pokok').val(response.gaji_pokok.toFixed(2));
                    $('#upah_lembur').val(response.upah_lembur.toFixed(2));
                    $('#gaji_tgl_merah').val(response.gaji_tgl_merah.toFixed(2));
                    $('#upah_lembur_tgl_merah').val(response.upah_lembur_tgl_merah.toFixed(2));
                    $('#iuran_bpjs_kantor').val(response.iuran_bpjs_kantor.toFixed(2));
                    $('#iuran_bpjs_karyawan').val(response.iuran_bpjs_karyawan.toFixed(2));

                    const bpjsKesehatanPerusahaan = (umk * 0.04).toFixed(2);
                    const bpjsJkk = (umk * 0.0089).toFixed(2);
                    const bpjsJhtPerusahaan = (umk * 0.037).toFixed(2);
                    const bpjsJkm = (umk * 0.003).toFixed(2);
                    const bpjsJpPerusahaan = (umk * 0.02).toFixed(2);

                    $('#bpjs_kesehatan_perusahaan').val(bpjsKesehatanPerusahaan);
                    $('#bpjs_jkk').val(bpjsJkk);
                    $('#bpjs_jht_perusahaan').val(bpjsJhtPerusahaan);
                    $('#bpjs_jkm').val(bpjsJkm);
                    $('#bpjs_jp_perusahaan').val(bpjsJpPerusahaan);

                    const bpjsKesehatanKaryawan = (umk * 0.01).toFixed(2);
                    const bpjsJhtKaryawan = (umk * 0.02).toFixed(2);
                    const bpjsJpKaryawan = (umk * 0.01).toFixed(2);

                    $('#bpjs_kesehatan_karyawan').val(bpjsKesehatanKaryawan);
                    $('#bpjs_jht_karyawan').val(bpjsJhtKaryawan);
                    $('#bpjs_jp_karyawan').val(bpjsJpKaryawan);

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
