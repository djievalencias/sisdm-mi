{{-- Row actions. Forms are siblings, never nested — nested forms are invalid HTML
     and the browser silently drops the inner one. --}}
<div style="display: inline-flex; gap: 6px; flex-wrap: wrap; align-items: center;">

    @if (!empty($show_url))
        <a href="{{ $show_url }}" class="btn btn-sm btn-secondary">{{ __('Show') }}</a>
    @endif

    @if (!empty($edit_url))
        <a href="{{ $edit_url }}" class="btn btn-sm btn-warning">{{ __('Edit') }}</a>
    @endif

    @if (!empty($archive_url))
        <form action="{{ url($archive_url) }}" method="post" style="display: inline; margin: 0;">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-sm btn-info"
                onclick="return confirm('{{ __('Are you sure you want to archive this data?') }}')">{{ __('Archive') }}</button>
        </form>
    @endif

    @if (!empty($restore_url))
        <form action="{{ url($restore_url) }}" method="post" style="display: inline; margin: 0;">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-sm btn-success"
                onclick="return confirm('{{ __('Restore this user?') }}')">{{ __('Restore') }}</button>
        </form>
    @endif

    @if (!empty($delete_url))
        <form action="{{ url($delete_url) }}" method="post" style="display: inline; margin: 0;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger"
                onclick="return confirm('{{ __('Are you sure you want to delete this data?') }}')">{{ __('Delete') }}</button>
        </form>
    @endif
</div>
