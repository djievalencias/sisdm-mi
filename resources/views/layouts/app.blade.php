<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('HRIS') }} · Mebel International</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- AdminLTE base -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables/datatables.min.css') }}"/>
    <!-- SISDM theme (must load after AdminLTE) -->
    <link rel="stylesheet" href="{{ asset('assets/css/sisdm-theme.css') }}">
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<div class="wrapper">

    <!-- Navbar -->
    @include('layouts._navbar')

    <!-- Sidebar -->
    <aside class="main-sidebar">
        @include('layouts._sidebar')
    </aside>

    <!-- Content -->
    <div class="content-wrapper">
        @yield('content')
    </div>

    <footer class="main-footer">
        <strong>{{ __('HRIS') }}</strong> — {{ __('Human Resource Information System') }}, CV Mebel International
    </footer>
</div>

<!-- Scripts -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/datatables/datatables.min.js') }}"></script>


@php
    // DataTables UI strings per locale; empty object means "use defaults" (English)
    $dtLang = app()->getLocale() === 'id' ? [
        'search' => 'Cari:',
        'lengthMenu' => 'Tampilkan _MENU_ data',
        'info' => 'Menampilkan _START_–_END_ dari _TOTAL_ data',
        'infoEmpty' => 'Tidak ada data',
        'infoFiltered' => '(disaring dari _MAX_ data)',
        'zeroRecords' => 'Data tidak ditemukan',
        'emptyTable' => 'Belum ada data',
        'processing' => 'Memproses...',
        'paginate' => ['first' => 'Awal', 'last' => 'Akhir', 'next' => 'Berikutnya', 'previous' => 'Sebelumnya'],
    ] : new \stdClass;
@endphp
<script>
    // Shared UI config: locale-aware DataTables strings + validation message
    window.SISDM = {
        locale: @json(app()->getLocale()),
        requiredMsg: @json(__('This field is required.')),
        dtLang: @json($dtLang)
    };

    // Client-side DataTables on any table marked si-datatable
    $(function () {
        $('table.si-datatable').each(function () {
            $(this).DataTable({
                responsive: true,
                language: window.SISDM.dtLang,
                columnDefs: [{ targets: 'no-sort', orderable: false, searchable: false }]
            });
        });
    });

    // Required-field feedback: block invalid submits, mark fields red, focus the first one
    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!form.classList || !form.classList.contains('needs-validation')) return;
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
            var bad = form.querySelectorAll(':invalid');
            Array.prototype.forEach.call(bad, function (el) {
                var group = el.closest('.form-group') || el.parentElement;
                if (group && !group.querySelector('.invalid-feedback')) {
                    var fb = document.createElement('div');
                    fb.className = 'invalid-feedback';
                    fb.textContent = window.SISDM.requiredMsg;
                    group.appendChild(fb);
                }
            });
            if (bad[0]) bad[0].focus();
        }
        form.classList.add('was-validated');
    }, true);
</script>

@stack('scripts')
</body>
</html>
