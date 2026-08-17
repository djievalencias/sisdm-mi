@if ($row->is_reviewed)
    <span class="si-pill green">{{ __('Reviewed by') }} {{ $row->reviewer_nama ?? '-' }}</span>
@else
    <span class="si-pill grey">{{ __('Not Reviewed') }}</span>
@endif
