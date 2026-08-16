@extends('layouts.app')

@section('content')
    <x-page :title="__('Leave Request Details')" :breadcrumb="__('Leave Requests')">
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <td>{{ $cutiPerizinan->user->nama }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Start Date') }}</th>
                            <td>{{ $cutiPerizinan->tanggal_mulai }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('End Date') }}</th>
                            <td>{{ $cutiPerizinan->tanggal_selesai }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Notes') }}</th>
                            <td>{{ $cutiPerizinan->keterangan }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Type') }}</th>
                            <td>{{ __(ucfirst($cutiPerizinan->jenis)) }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Status') }}</th>
                            <td>
                                <span class="si-pill {{ $cutiPerizinan->status_pengajuan == 'diajukan' ? 'amber' : ($cutiPerizinan->status_pengajuan == 'disetujui' ? 'green' : 'red') }}">
                                    {{ __(ucfirst($cutiPerizinan->status_pengajuan)) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Approved By') }}</th>
                            <td>{{ $cutiPerizinan->disetujuiOleh->nama ?? __('Not yet approved') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Supporting Document') }}</th>
                            <td>
                                @if ($cutiPerizinan->surat_izin)
                                    <a href="{{ asset('storage/' . $cutiPerizinan->surat_izin) }}" target="_blank" class="btn btn-sm btn-primary">{{ __('View File') }}</a>
                                @else
                                    <span class="text-muted">{{ __('No file') }}</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('cuti-perizinan.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> {{ __('Back') }}</a>
                @role('admin')
                    <a href="{{ route('cuti-perizinan.edit', $cutiPerizinan->id) }}" class="btn btn-warning">{{ __('Edit') }}</a>
                    <form action="{{ route('cuti-perizinan.destroy', $cutiPerizinan->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('{{ __('Are you sure you want to delete this data?') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                    </form>
                @endrole
                @can('approve', $cutiPerizinan)
                    <form action="{{ route('cuti-perizinan.approve', $cutiPerizinan->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">{{ __('Approve') }}</button>
                    </form>
                    <form action="{{ route('cuti-perizinan.reject', $cutiPerizinan->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary">{{ __('Reject') }}</button>
                    </form>
                @endcan
                @can('undo', $cutiPerizinan)
                    <form action="{{ route('cuti-perizinan.undo', $cutiPerizinan->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-warning">{{ __('Undo') }}</button>
                    </form>
                @endcan
            </div>
        </div>
    </x-page>
@endsection
