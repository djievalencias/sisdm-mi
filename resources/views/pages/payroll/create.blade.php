@extends('layouts.app')

@section('content')
    <x-page :title="__('Add Payroll')" :breadcrumb="__('Payroll')">
        <form method="POST" action="{{ route('payroll.store') }}" id="payrollForm">
            @csrf

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Payroll Information') }}</h3>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="id_user">{{ __('Employee') }} <span class="text-danger">*</span></label>
                            <select name="id_user" id="id_user" class="form-control @error('id_user') is-invalid @enderror" required>
                                <option value="">{{ __('Select employee') }}</option>
                                @foreach ($users as $u)
                                    <option value="{{ $u->id }}" {{ old('id_user') == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                                @endforeach
                            </select>
                            @error('id_user')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tanggal_payroll">{{ __('Payroll Date:') }} <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_payroll" id="tanggal_payroll" class="form-control @error('tanggal_payroll') is-invalid @enderror"
                                value="{{ old('tanggal_payroll') }}" required>
                            @error('tanggal_payroll')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="umk">{{ __('UMK (City Minimum Wage):') }} <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="umk" id="umk" class="form-control @error('umk') is-invalid @enderror"
                                value="{{ old('umk', 3454827) }}" required>
                            @error('umk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Salary Calculation (With Formula Details)') }}</h3>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="gaji_per_hari">{{ __('Daily Wage (IDR):') }}</label>
                            <input type="text" id="gaji_per_hari" class="form-control" readonly>
                            <small class="text-muted">{{ __('Formula: UMK / 25') }}</small>
                        </div>
                    </div>

                    <hr>

                    <h5>{{ __('Base Salary and Overtime') }}</h5>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="total_hari_kerja">{{ __('Total Workdays:') }}</label>
                            <input type="text" id="total_hari_kerja" name="total_hari_kerja" class="form-control" readonly>
                            <small class="text-muted">{{ __("Based on the employee's attendance in one month") }}</small>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="gaji_pokok">{{ __('Base Salary (IDR):') }}</label>
                            <input type="text" name="gaji_pokok" id="gaji_pokok" class="form-control" readonly>
                            <small class="text-muted">{{ __('Formula: Total Workdays × Daily Wage') }}</small>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="upah_lembur">{{ __('Overtime Pay (IDR):') }}</label>
                            <input type="text" name="upah_lembur" id="upah_lembur" class="form-control" readonly>
                            <small class="text-muted">{{ __('Formula: Total Overtime Hours × 1.5 × (Daily Wage / 7)') }}</small>
                        </div>
                    </div>

                    <hr>

                    <h5>{{ __('Holiday Pay and Overtime') }}</h5>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="gaji_tgl_merah">{{ __('Holiday Pay (IDR):') }}</label>
                            <input type="text" name="gaji_tgl_merah" id="gaji_tgl_merah" class="form-control" readonly>
                            <small class="text-muted">{{ __('Formula: Holiday Workdays × 2 × Daily Wage') }}</small>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="upah_lembur_tgl_merah">{{ __('Holiday Overtime Pay (IDR):') }}</label>
                            <input type="text" name="upah_lembur_tgl_merah" id="upah_lembur_tgl_merah" class="form-control" readonly>
                            <small class="text-muted">{{ __('Formula: Holiday Overtime Hours × 2 × (Daily Wage / 7)') }}</small>
                        </div>
                    </div>

                    <hr>

                    <h5>{{ __('BPJS Details') }}</h5>
                    <h6><strong>{{ __('BPJS Paid by the Company') }}</strong></h6>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="bpjs_kesehatan_perusahaan">{{ __('BPJS Health (4% UMK):') }}</label>
                            <input type="text" id="bpjs_kesehatan_perusahaan" class="form-control" readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="bpjs_jkk">{{ __('BPJS JKK (0.89% UMK):') }}</label>
                            <input type="text" id="bpjs_jkk" class="form-control" readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="bpjs_jht_perusahaan">{{ __('BPJS JHT (3.7% UMK):') }}</label>
                            <input type="text" id="bpjs_jht_perusahaan" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="bpjs_jkm">{{ __('BPJS JKM (0.3% UMK):') }}</label>
                            <input type="text" id="bpjs_jkm" class="form-control" readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="bpjs_jp_perusahaan">{{ __('BPJS JP (2% UMK):') }}</label>
                            <input type="text" id="bpjs_jp_perusahaan" class="form-control" readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="iuran_bpjs_kantor">{{ __('Total Company BPJS Contribution:') }}</label>
                            <input type="text" name="iuran_bpjs_kantor" id="iuran_bpjs_kantor" class="form-control" readonly>
                        </div>
                    </div>

                    <h6 class="mt-3"><strong>{{ __('BPJS Paid by the Employee') }}</strong></h6>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="bpjs_kesehatan_karyawan">{{ __('BPJS Health (1% UMK):') }}</label>
                            <input type="text" id="bpjs_kesehatan_karyawan" class="form-control" readonly>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="bpjs_jht_karyawan">{{ __('BPJS JHT (2% UMK):') }}</label>
                            <input type="text" id="bpjs_jht_karyawan" class="form-control" readonly>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="bpjs_jp_karyawan">{{ __('BPJS JP (1% UMK):') }}</label>
                            <input type="text" id="bpjs_jp_karyawan" class="form-control" readonly>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="iuran_bpjs_karyawan">{{ __('Total Employee BPJS Contribution:') }}</label>
                            <input type="text" name="iuran_bpjs_karyawan" id="iuran_bpjs_karyawan" class="form-control" readonly>
                        </div>
                    </div>

                    <hr>

                    <h5>{{ __('Total Take Home Pay') }}</h5>
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label for="take_home_pay">{{ __('Total Take Home Pay (IDR):') }}</label>
                            <input type="text" name="take_home_pay" id="take_home_pay" class="form-control" readonly>
                            <small class="text-muted">{{ __('Formula: Base Salary + Overtime + Holiday Pay + Holiday Overtime + Company BPJS - Employee BPJS') }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    <a href="{{ route('payroll.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                </div>
            </div>
        </form>
    </x-page>
@endsection

@push('scripts')
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

                    $('#total_hari_kerja').val(response.total_hari_kerja + " {{ __('days') }}");
                    $('#gaji_pokok').val(response.gaji_pokok.toFixed(2));
                    $('#upah_lembur').val(response.upah_lembur.toFixed(2));
                    $('#gaji_tgl_merah').val(response.gaji_tgl_merah.toFixed(2));
                    $('#upah_lembur_tgl_merah').val(response.upah_lembur_tgl_merah.toFixed(2));
                    $('#iuran_bpjs_kantor').val(response.iuran_bpjs_kantor.toFixed(2));
                    $('#iuran_bpjs_karyawan').val(response.iuran_bpjs_karyawan.toFixed(2));

                    $('#bpjs_kesehatan_perusahaan').val((umk * 0.04).toFixed(2));
                    $('#bpjs_jkk').val((umk * 0.0089).toFixed(2));
                    $('#bpjs_jht_perusahaan').val((umk * 0.037).toFixed(2));
                    $('#bpjs_jkm').val((umk * 0.003).toFixed(2));
                    $('#bpjs_jp_perusahaan').val((umk * 0.02).toFixed(2));

                    $('#bpjs_kesehatan_karyawan').val((umk * 0.01).toFixed(2));
                    $('#bpjs_jht_karyawan').val((umk * 0.02).toFixed(2));
                    $('#bpjs_jp_karyawan').val((umk * 0.01).toFixed(2));

                    $('#take_home_pay').val(response.take_home_pay.toFixed(2));
                },
                error: function (xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        }
    });
</script>
@endpush
