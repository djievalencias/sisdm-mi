@extends('layouts.app')

@section('content')
    <!-- Page header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('Dashboard') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Dashboard') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if (auth()->user()->is_admin)
                <!-- ============ ADMIN DASHBOARD ============ -->

                <!-- Stat cards -->
                <div class="row mb-3">
                    <div class="col-6 col-lg-3 mb-2">
                        <div class="si-stat">
                            <div class="si-stat-icon"><i class="fas fa-users"></i></div>
                            <div>
                                <div class="si-stat-num">{{ $totalKaryawan }}</div>
                                <div class="si-stat-label">{{ __('Active employees') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 mb-2">
                        <div class="si-stat">
                            <div class="si-stat-icon"><i class="fas fa-fingerprint"></i></div>
                            <div>
                                <div class="si-stat-num">{{ $hadirHariIni }}</div>
                                <div class="si-stat-label">{{ __('Present today') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 mb-2">
                        <div class="si-stat">
                            <div class="si-stat-icon amber"><i class="fas fa-hourglass-half"></i></div>
                            <div>
                                <div class="si-stat-num">{{ $belumPulang }}</div>
                                <div class="si-stat-label">{{ __('Not clocked out') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 mb-2">
                        <div class="si-stat">
                            <div class="si-stat-icon red"><i class="fas fa-envelope-open-text"></i></div>
                            <div>
                                <div class="si-stat-num">{{ $cutiPending }}</div>
                                <div class="si-stat-label">{{ __('Pending leave requests') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Attendance chart -->
                    <div class="col-lg-8 mb-3">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title mb-0">{{ __('Attendance overview') }}</h3>
                                <a href="{{ url('/attendance') }}" class="btn btn-sm btn-info">{{ __('View all') }}</a>
                            </div>
                            <div class="card-body">
                                {{-- fixed height: the chart container inherits its height, and inside
                                     an auto-growing card the renderer resizes itself in a loop --}}
                                <div style="height: 300px; position: relative;">
                                    {!! $attendanceChart->container() !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Latest announcements -->
                    <div class="col-lg-4 mb-3">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title mb-0">{{ __('Latest announcements') }}</h3>
                                <a href="{{ url('/pengumuman') }}" class="btn btn-sm btn-info">{{ __('View all') }}</a>
                            </div>
                            <div class="card-body py-2">
                                @forelse ($pengumuman as $item)
                                    <ul class="si-list">
                                        <li>
                                            <p class="t">{{ $item->judul }}</p>
                                            <p class="m">{{ \Illuminate\Support\Str::limit($item->pesan, 70) }}</p>
                                            <p class="m">{{ $item->created_at->diffForHumans() }} · {{ $item->creator->nama ?? '—' }}</p>
                                        </li>
                                    </ul>
                                @empty
                                    <p class="text-muted my-3">{{ __('No announcements yet.') }}</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <!-- ============ EMPLOYEE DASHBOARD ============ -->

                <div class="row">
                    <div class="col-lg-5 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                {{-- no card-title class: AdminLTE floats it, which would pull the next line up beside the greeting --}}
                                <h3 class="mb-3" style="font-size: 1.1rem; font-weight: 700;">
                                    {{ __('Hi') }}, {{ collect(explode(' ', auth()->user()->nama))->first() }} 👋
                                </h3>

                                <p class="mb-2" style="font-size: 13px; font-weight: 600;">{{ __("Today's attendance") }}</p>
                                @if (isset($absenHariIni) && $absenHariIni)
                                    @if ($absenHariIni->status)
                                        <span class="si-pill green">{{ __('Clocked in & out') }}</span>
                                    @else
                                        <span class="si-pill amber">{{ __('Clocked in — not yet out') }}</span>
                                    @endif
                                @else
                                    <span class="si-pill red">{{ __('Not clocked in yet') }}</span>
                                @endif

                                <p class="text-muted mt-3 mb-0" style="font-size: 12.5px;">
                                    {{ __('Clock in and out from the mobile app. Your attendance history and payslips are available there as well.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7 mb-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h3 class="card-title mb-0">{{ __('Latest announcements') }}</h3>
                            </div>
                            <div class="card-body py-2">
                                @forelse ($pengumuman as $item)
                                    <ul class="si-list">
                                        <li>
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="mr-2">
                                                    <p class="t">{{ $item->judul }}</p>
                                                    <p class="m">{{ \Illuminate\Support\Str::limit($item->pesan, 90) }}</p>
                                                    <p class="m">{{ $item->created_at->diffForHumans() }}</p>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-info flex-shrink-0 announcement-detail"
                                                    data-judul="{{ $item->judul }}"
                                                    data-pesan="{{ $item->pesan }}"
                                                    data-foto="{{ $item->foto ? asset('storage/' . $item->foto) : '' }}"
                                                    data-tanggal="{{ $item->created_at->translatedFormat('d F Y, H:i') }}">
                                                    {{ __('Detail') }}
                                                </button>
                                            </div>
                                        </li>
                                    </ul>
                                @empty
                                    <p class="text-muted my-3">{{ __('No announcements yet.') }}</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Announcement detail modal -->
                <div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="announcementModalTitle"></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <img id="announcementModalFoto" src="" alt="" class="img-fluid rounded mb-3 d-none">
                                <p id="announcementModalPesan" style="white-space: pre-line;"></p>
                                <p class="text-muted mb-0" style="font-size: 12.5px;" id="announcementModalTanggal"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </section>
@endsection

@push('scripts')
    @if (auth()->user()->is_admin)
        {{-- AttendanceChart extends Chartjs\Chart, so Chart.js is the renderer (vendored locally) --}}
        <script src="{{ asset('assets/plugins/chartjs/chart.umd.js') }}"></script>
        {!! $attendanceChart->script() !!}
    @else
        <script>
            $(document).ready(function () {
                $('.announcement-detail').on('click', function () {
                    $('#announcementModalTitle').text($(this).data('judul'));
                    $('#announcementModalPesan').text($(this).data('pesan'));
                    $('#announcementModalTanggal').text($(this).data('tanggal'));

                    var foto = $(this).data('foto');
                    $('#announcementModalFoto').toggleClass('d-none', !foto).attr('src', foto || '');

                    $('#announcementModal').modal('show');
                });
            });
        </script>
    @endif
@endpush
