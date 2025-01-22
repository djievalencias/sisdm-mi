@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">User</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">User</li>
                        <li class="breadcrumb-item active">Show</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <section class="col-lg-12">
                    <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary mb-2">Back</a>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="ion ion-clipboard mr-1"></i>
                                User
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table" id="datatable">
                                <tbody>
                                    <tr>
                                        <th>Nama</th>
                                        <td>{{ $user->nama }}</td>
                                    </tr>
                                    <tr>
                                        <th>NIK</th>
                                        <td>{{ $user->nik }}</td>
                                    </tr>
                                    <tr>
                                        <th>E-Mail</th>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>NPWP</th>
                                        <td>{{ $user->npwp }}</td>
                                    </tr>
                                    <tr>
                                        <th>No. HP</th>
                                        <td>{{ $user->no_telepon }}</td>
                                    </tr>
                                    <tr>
                                        <th>Gender</th>
                                        <td>{{ $user->jenis_kelamin == 'M' ? 'Male' : ($user->jenis_kelamin == 'F' ? 'Female' : 'Not specified') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tempat Lahir</th>
                                        <td>{{ $user->tempat_lahir }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Lahir</th>
                                        <td>{{ $user->tanggal_lahir }}</td>
                                    </tr>
                                    <tr>
                                        <th>Agama</th>
                                        <td>{{ $user->agama }}</td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <td>{{ $user->alamat }}</td>
                                    </tr>
                                    <tr>
                                        <th>RT</th>
                                        <td>{{ $user->rt }}</td>
                                    </tr>
                                    <tr>
                                        <th>RW</th>
                                        <td>{{ $user->rw }}</td>
                                    </tr>
                                    <tr>
                                        <th>Desa/Kelurahan</th>
                                        <td>{{ $user->kelurahan }}</td>
                                    </tr>
                                    <tr>
                                        <th>Kecamatan</th>
                                        <td>{{ $user->kecamatan }}</td>
                                    </tr>
                                    <tr>
                                        <th>Kabupaten/Kota</th>
                                        <td>{{ $user->kabupaten_kota }}</td>
                                    </tr>
                                    <tr>
                                        <th>Is Active?</th>
                                        <td>{{ $user->is_aktif ? 'Yes' : 'No' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Is Admin?</th>
                                        <td>{{ $user->is_admin ? 'Yes' : 'No' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Foto Profil</th>
                                        <td>
                                            @if ($user->foto_profil)
                                                <img src="{{ Storage::url($user->foto_profil) }}" alt="Profile Photo"
                                                    width="350">
                                            @else
                                                No photo available
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Foto KTP</th>
                                        <td>
                                            @if ($user->foto_ktp)
                                                <img src="{{ Storage::url($user->foto_ktp) }}" alt="KTP Photo"
                                                    width="350">
                                            @else
                                                No photo available
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>BPJS Kesehatan</th>
                                        <td>
                                            @if ($user->foto_bpjs_kesehatan)
                                                <img src="{{ Storage::url($user->foto_bpjs_kesehatan) }}"
                                                    alt="BPJS Kesehatan" width="350">
                                            @else
                                                No photo available
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>BPJS Ketenagakerjaan</th>
                                        <td>
                                            @if ($user->foto_bpjs_ketenagakerjaan)
                                                <img src="{{ Storage::url($user->foto_bpjs_ketenagakerjaan) }}"
                                                    alt="BPJS Ketenagakerjaan" width="350">
                                            @else
                                                No photo available
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection
