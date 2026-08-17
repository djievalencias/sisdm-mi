{{-- Row actions for the server-side payroll table: an unreviewed payroll can
     still be edited/deleted; once reviewed it can only be viewed, downloaded
     as a payslip, and marked paid. --}}
<div style="display: inline-flex; gap: 6px; flex-wrap: wrap; align-items: center;">
    @if (!$row->is_reviewed)
        <a href="{{ route('payroll.review', $row->id) }}" class="btn btn-info btn-sm">{{ __('Review') }}</a>
        <a href="{{ route('payroll.edit', $row->id) }}" class="btn btn-warning btn-sm">{{ __('Edit') }}</a>
        <form action="{{ route('payroll.destroy', $row->id) }}" method="POST" style="display: inline; margin: 0;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm"
                onclick="return confirm('{{ __('Are you sure you want to delete this data?') }}')">{{ __('Delete') }}</button>
        </form>
    @else
        <a href="{{ route('payroll.review', $row->id) }}" class="btn btn-info btn-sm">{{ __('Detail') }}</a>
        <a href="{{ route('payroll.slip', $row->id) }}" class="btn btn-secondary btn-sm">{{ __('Payslip') }}</a>
        @if (!$row->status_pembayaran)
            <form action="{{ route('payroll.markAsPaid', $row->id) }}" method="POST" style="display: inline; margin: 0;">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-success btn-sm"
                    onclick="return confirm('{{ __('Are you sure you want to mark this salary as paid? This action cannot be undone.') }}')">{{ __('Mark as Paid') }}</button>
            </form>
        @endif
    @endif
</div>
