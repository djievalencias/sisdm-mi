@if ($row->status_pembayaran)
    <span class="si-pill green">{{ __('Paid') }}</span>
@else
    <span class="si-pill red">{{ __('Unpaid') }}</span>
@endif
