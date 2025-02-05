@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-warning text-white">
            Edit Riwayat Jabatan
        </div>
        <div class="card-body">
            
            <!-- Display Errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('riwayat_jabatan.update', [$user->id, $riwayatJabatan->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="user_id" value="{{ $user->id }}">
                
                <!-- Jabatan Dropdown -->
                <div class="mb-3">
                    <label for="jabatan" class="form-label">Jabatan</label>
                    <select name="id_jabatan" id="jabatan" class="form-select">
                        <option value="">-- Pilih Jabatan --</option>
                        @foreach ($jabatanList as $jabatan)
                            <option value="{{ $jabatan->id }}"
                                data-kantor="{{ $jabatan->grup->departemen->kantor->nama ?? '' }}"
                                data-departemen="{{ $jabatan->grup->departemen->nama ?? '' }}"
                                data-grup="{{ $jabatan->grup->nama ?? '' }}"
                                {{ $jabatan->id == $riwayatJabatan->id_jabatan ? 'selected' : '' }}>
                                {{ $jabatan->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kantor, Departemen, Grup -->
                <div class="mb-3">
                    <label class="form-label">Kantor</label>
                    <input type="text" id="kantor" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Departemen</label>
                    <input type="text" id="departemen" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Grup</label>
                    <input type="text" id="grup" class="form-control" readonly>
                </div>

                <!-- Start Date -->
                <div class="mb-3">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai', $riwayatJabatan->tanggal_mulai) }}" required>
                </div>

                <!-- End Date -->
                <div class="mb-3">
                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai (Opsional)</label>
                    <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai', $riwayatJabatan->tanggal_selesai) }}">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('jabatan').addEventListener('change', function () {
        var selectedOption = this.options[this.selectedIndex];
        document.getElementById('kantor').value = selectedOption.getAttribute('data-kantor') || '';
        document.getElementById('departemen').value = selectedOption.getAttribute('data-departemen') || '';
        document.getElementById('grup').value = selectedOption.getAttribute('data-grup') || '';
    });

    // Set initial values on page load
    window.onload = function() {
        var selectedOption = document.getElementById('jabatan').options[document.getElementById('jabatan').selectedIndex];
        document.getElementById('kantor').value = selectedOption.getAttribute('data-kantor') || '';
        document.getElementById('departemen').value = selectedOption.getAttribute('data-departemen') || '';
        document.getElementById('grup').value = selectedOption.getAttribute('data-grup') || '';
    }
</script>

@endsection
