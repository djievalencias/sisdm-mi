<form action="{{ url($delete_url) }}" method="post" style="display: inline-flex; gap: 5px;">
    @csrf
    @method('DELETE')

    <a href="{{ $show_url }}" class="btn btn-sm btn-secondary">Show</a>

    @if ($edit_url)
        <a href="{{ $edit_url }}" class="btn btn-sm btn-warning text-white">Edit</a>
    @endif

    @if ($archive_url)
        <form action="{{ url($archive_url) }}" method="post" style="display:inline-block;">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-sm btn-info text-white" 
                onclick="return confirm('Are you sure want to archive this data?')">Archive</button>
        </form>
    @endif

    @if ($delete_url)
        <button type="submit" class="btn btn-danger btn-sm" 
            onclick="return confirm('Are you sure want to delete this data?')">Delete</button>
    @endif
</form>
