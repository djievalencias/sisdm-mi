@extends('layouts.app')

@section('content')
    <x-page :title="__('Work Calendar')">

        @include('layouts._toolbar', ['create_url' => route('kalender.create'), 'create_label' => __('Add Event')])

        <div class="card">
            <div class="card-body">
                <table class="table si-datatable">
                    <thead>
                        <tr>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Start Date') }}</th>
                            <th>{{ __('End Date') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th class="no-sort">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                            <tr>
                                <td>{{ $event->judul }}</td>
                                <td>{{ $event->tanggal_mulai }}</td>
                                <td>{{ $event->tanggal_selesai ?? '-' }}</td>
                                <td>{{ ucfirst($event->tipe) }}</td>
                                <td>
                                    <a href="{{ route('kalender.show', $event->id) }}" class="btn btn-info btn-sm">{{ __('View') }}</a>
                                    <a href="{{ route('kalender.edit', $event->id) }}" class="btn btn-warning btn-sm">{{ __('Edit') }}</a>
                                    <form action="{{ route('kalender.destroy', $event->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('{{ __('Are you sure you want to delete this data?') }}')">{{ __('Delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </x-page>
@endsection
