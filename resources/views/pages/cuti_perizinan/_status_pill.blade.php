<span class="si-pill {{ $row->status_pengajuan == 'diajukan' ? 'amber' : ($row->status_pengajuan == 'disetujui' ? 'green' : 'red') }}">
    {{ __(ucfirst($row->status_pengajuan)) }}
</span>
