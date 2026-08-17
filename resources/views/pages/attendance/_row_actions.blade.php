{{-- Row actions for the server-side attendance table. --}}
<div style="display: inline-flex; gap: 6px; flex-wrap: wrap; align-items: center;">
    <a href="{{ route('attendance.show', $row->id) }}" class="btn btn-sm btn-info">{{ __('Detail') }}</a>
    <a href="{{ route('attendance.edit', $row->id) }}" class="btn btn-sm btn-warning">{{ __('Edit') }}</a>
    <form action="{{ route('attendance.destroy', $row->id) }}" method="POST" style="display: inline; margin: 0;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger"
            onclick="return confirm('{{ __('Are you sure you want to delete this data?') }}')">{{ __('Delete') }}</button>
    </form>
</div>
