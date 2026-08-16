@extends('layouts.app')

@section('content')
    <x-page :title="__('Activity Log')">

        @include('layouts._toolbar', [
            'filter_action' => route('activity-log.index'),
            'filters' => [
                ['type' => 'select', 'name' => 'description', 'label' => __('Action'), 'options' => $labels],
                ['type' => 'select', 'name' => 'causer_id', 'label' => __('User'), 'options' => $users->pluck('nama', 'id')],
                ['type' => 'date', 'name' => 'from', 'label' => __('From')],
                ['type' => 'date', 'name' => 'to', 'label' => __('To')],
            ],
        ])

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table si-datatable">
                        <thead>
                            <tr>
                                <th>{{ __('Time') }}</th>
                                <th>{{ __('User') }}</th>
                                <th>{{ __('Action') }}</th>
                                <th>{{ __('Subject') }}</th>
                                <th class="no-sort">{{ __('Details') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activities as $activity)
                                <tr>
                                    <td data-order="{{ $activity->created_at->timestamp }}">{{ $activity->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $activity->causer->nama ?? __('System') }}</td>
                                    <td>{{ $labels[$activity->description] ?? $activity->description }}</td>
                                    <td>{{ $activity->subject_type ? class_basename($activity->subject_type) . ' #' . $activity->subject_id : '-' }}</td>
                                    <td>
                                        @if ($activity->properties->isNotEmpty())
                                            <code>{{ Str::limit(json_encode($activity->properties), 120) }}</code>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-page>
@endsection
