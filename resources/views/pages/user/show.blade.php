@extends('layouts.app')

@section('content')
    <x-page :title="__('Employee Details')" :breadcrumb="__('Employees')">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('User Details') }}</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <td>{{ $user->nama }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('NIK') }}</th>
                            <td>{{ $user->nik }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('NPWP') }}</th>
                            <td>{{ $user->npwp }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('E-mail') }}</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Phone Number') }}</th>
                            <td>{{ $user->no_telepon }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Gender') }}</th>
                            <td>{{ $user->jenis_kelamin == 'L' ? __('Male') : ($user->jenis_kelamin == 'P' ? __('Female') : __('Not specified')) }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Place of Birth') }}</th>
                            <td>{{ $user->tempat_lahir }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Date of Birth') }}</th>
                            <td>{{ $user->tanggal_lahir }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Religion') }}</th>
                            <td>{{ $user->agama }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Supervisor') }}</th>
                            <td>{{ $user->atasan->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Address') }}</th>
                            <td>{{ $user->alamat }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('RT') }}</th>
                            <td>{{ $user->rt }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('RW') }}</th>
                            <td>{{ $user->rw }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Village') }}</th>
                            <td>{{ $user->kelurahan }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('District') }}</th>
                            <td>{{ $user->kecamatan }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('City/Regency') }}</th>
                            <td>{{ $user->kabupaten_kota }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Active?') }}</th>
                            <td>{{ $user->is_aktif ? __('Yes') : __('No') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Administrator?') }}</th>
                            <td>{{ $user->is_admin ? __('Yes') : __('No') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Photos') }}</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <tbody>
                        @foreach ([
                            'foto_profil' => __('Profile Photo'),
                            'foto_ktp' => __('ID Card Photo'),
                            'foto_bpjs_kesehatan' => __('BPJS Health Card'),
                            'foto_bpjs_ketenagakerjaan' => __('BPJS Employment Card'),
                        ] as $field => $label)
                            <tr>
                                <th>{{ $label }}</th>
                                <td>
                                    @if ($user->$field)
                                        <img src="{{ asset('storage/' . $user->$field) }}" alt="{{ $label }}" width="100" class="img-thumbnail">
                                    @else
                                        <span class="text-muted">{{ __('No photo available') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Job History') }}</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('Office') }}</th>
                            <th>{{ __('Department') }}</th>
                            <th>{{ __('Group') }}</th>
                            <th>{{ __('Position') }}</th>
                            <th>{{ __('Start Date') }}</th>
                            <th>{{ __('End Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($user->riwayatJabatan && $user->riwayatJabatan->count())
                            @foreach ($user->riwayatJabatan as $riwayatJabatan)
                                <tr>
                                    <td>{{ $riwayatJabatan->jabatan->grup->departemen->kantor->nama ?? '-' }}</td>
                                    <td>{{ $riwayatJabatan->jabatan->grup->departemen->nama ?? '-' }}</td>
                                    <td>{{ $riwayatJabatan->jabatan->grup->nama ?? '-' }}</td>
                                    <td>{{ $riwayatJabatan->jabatan->nama ?? '-' }}</td>
                                    <td>{{ $riwayatJabatan->tanggal_mulai }}</td>
                                    <td>{{ $riwayatJabatan->tanggal_selesai ?? __('Present') }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center"><strong>{{ __('No job history yet') }}</strong></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('user.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> {{ __('Back') }}</a>
                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-warning">{{ __('Edit') }}</a>
                @if ($user->is_archived)
                    <form action="{{ route('user.restore', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success">{{ __('Restore') }}</button>
                    </form>
                @else
                    <form action="{{ route('user.archive', $user->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('{{ __('Are you sure you want to archive this data?') }}');">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-danger">{{ __('Archive') }}</button>
                    </form>
                @endif
                <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('{{ __('Are you sure you want to delete this data?') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                </form>
            </div>
        </div>
    </x-page>
@endsection
