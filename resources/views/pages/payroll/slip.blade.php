<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('Payslip') }} — {{ $payroll->user->nama }}</title>
    <style>
        /* Palette mirrors public/assets/css/sisdm-theme.css (navy #2B3990,
           ink #1D2733/#5B6B7B, rule #E3E8EE, ground #F6F8FA, soft #E9EBF7). */
        body { font-family: "DejaVu Sans", sans-serif; font-size: 12px; color: #1D2733; margin: 24px; }
        .header { border-bottom: 2px solid #2B3990; padding-bottom: 10px; margin-bottom: 16px; }
        .header table { border: none; margin: 0; }
        .header td { border: none; padding: 0; vertical-align: middle; }
        .header .logo-cell { width: 170px; }
        .header img { height: 42px; }
        .brand { font-size: 20px; font-weight: bold; color: #2B3990; }
        .sub { font-size: 12px; color: #5B6B7B; }
        h2 { font-size: 14px; margin: 18px 0 6px; color: #2B3990; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th, td { border: 1px solid #E3E8EE; padding: 6px 8px; text-align: left; }
        th { background: #F6F8FA; }
        td.amount, th.amount { text-align: right; }
        .total-row td { font-weight: bold; background: #E9EBF7; color: #2B3990; }
        .stamps { margin-top: 18px; font-size: 11px; color: #5B6B7B; }
        .meta td, .meta th { border: none; padding: 2px 0; }
    </style>
</head>
<body>
    <div class="header">
        <table width="100%">
            <tr>
                {{-- dompdf resolves local filesystem paths (within its chroot); never use asset() URLs here --}}
                <td class="logo-cell"><img src="{{ public_path('assets/img/logo-full.png') }}" alt="Mebel International"></td>
                <td style="text-align: right;">
                    <div class="brand">{{ __('HRIS') }}</div>
                    <div class="sub">CV Mebel International — {{ __('Payslip') }} {{ $payroll->tanggal_payroll->translatedFormat('F Y') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="meta">
        <tr>
            <th style="width: 30%">{{ __('Employee') }}</th>
            <td>{{ $payroll->user->nama }}</td>
        </tr>
        <tr>
            <th>{{ __('E-mail') }}</th>
            <td>{{ $payroll->user->email }}</td>
        </tr>
        <tr>
            <th>{{ __('Period') }}</th>
            <td>{{ $payroll->tanggal_payroll->translatedFormat('F Y') }}</td>
        </tr>
    </table>

    <h2>{{ __('Earnings') }}</h2>
    <table>
        <tr>
            <td>{{ __('Base Salary') }}</td>
            <td class="amount">Rp {{ number_format($payroll->gaji_pokok, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>{{ __('Overtime Pay') }}</td>
            <td class="amount">Rp {{ number_format($payroll->upah_lembur, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>{{ __('Holiday Pay') }}</td>
            <td class="amount">Rp {{ number_format($payroll->gaji_tgl_merah, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>{{ __('Holiday Overtime Pay') }}</td>
            <td class="amount">Rp {{ number_format($payroll->upah_lembur_tgl_merah, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>{{ __('Company BPJS Contribution') }}</td>
            <td class="amount">Rp {{ number_format($payroll->iuran_bpjs_kantor, 0, ',', '.') }}</td>
        </tr>
        @foreach ($payroll->tunjangan as $item)
            <tr>
                <td>{{ __('Allowance') }} — {{ $item->nama }}</td>
                <td class="amount">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>

    <h2>{{ __('Deductions') }}</h2>
    <table>
        <tr>
            <td>{{ __('Employee BPJS Contribution') }}</td>
            <td class="amount">Rp {{ number_format($payroll->iuran_bpjs_karyawan, 0, ',', '.') }}</td>
        </tr>
        @foreach ($payroll->potongan as $item)
            <tr>
                <td>{{ __('Deduction') }} — {{ $item->nama }}</td>
                <td class="amount">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>

    <table>
        <tr class="total-row">
            <td>{{ __('Take-Home Pay') }}</td>
            <td class="amount">Rp {{ number_format($payroll->take_home_pay, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="stamps">
        @if ($payroll->is_reviewed)
            <div>{{ __('Reviewed by') }} {{ $payroll->reviewer->nama ?? '-' }} — {{ optional($payroll->reviewed_at)->format('d M Y, H:i') }}</div>
        @endif
        @if ($payroll->status_pembayaran)
            <div>{{ __('Paid on') }} {{ optional($payroll->dibayar_at)->format('d M Y, H:i') }}</div>
        @endif
    </div>
</body>
</html>
