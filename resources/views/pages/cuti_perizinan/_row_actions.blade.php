{{-- Row actions for the server-side leave tables. Approve/Reject/Undo are
     policy-gated per row, so a supervisor only ever sees what they may act on. --}}
<div style="display: inline-flex; gap: 6px; flex-wrap: wrap; align-items: center;">
    <a href="{{ route('cuti-perizinan.show', $row->id) }}" class="btn btn-info btn-sm">{{ __('Detail') }}</a>

    @role('admin')
        <a href="{{ route('cuti-perizinan.edit', $row->id) }}" class="btn btn-warning btn-sm">{{ __('Edit') }}</a>
        <form action="{{ route('cuti-perizinan.destroy', $row->id) }}" method="POST" style="display: inline; margin: 0;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm"
                onclick="return confirm('{{ __('Are you sure you want to delete this data?') }}')">{{ __('Delete') }}</button>
        </form>
    @endrole

    @can('approve', $row)
        <form action="{{ route('cuti-perizinan.approve', $row->id) }}" method="POST" style="display: inline; margin: 0;">
            @csrf
            <button type="submit" class="btn btn-success btn-sm">{{ __('Approve') }}</button>
        </form>
        <form action="{{ route('cuti-perizinan.reject', $row->id) }}" method="POST" style="display: inline; margin: 0;">
            @csrf
            <button type="submit" class="btn btn-outline-secondary btn-sm">{{ __('Reject') }}</button>
        </form>
    @endcan

    @can('undo', $row)
        <form action="{{ route('cuti-perizinan.undo', $row->id) }}" method="POST" style="display: inline; margin: 0;">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm">{{ __('Undo') }}</button>
        </form>
    @endcan
</div>
