@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            Create Riwayat Jabatan
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

            <form action="{{ route('riwayat_jabatan.store', $user->id) }}" method="POST">
                @csrf
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
                                data-grup="{{ $jabatan->grup->nama ?? '' }}">
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
                    <input type="date" name="tanggal_mulai" class="form-control" required>
                </div>

                <!-- End Date -->
                <div class="mb-3">
                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai (Opsional)</label>
                    <input type="date" name="tanggal_selesai" class="form-control">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success">Submit</button>
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
</script>

@endsection
