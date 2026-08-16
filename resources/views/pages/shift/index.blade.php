@extends('layouts.app')

@section('content')
    <x-page :title="__('Shifts')">

        @include('layouts._toolbar', ['create_url' => route('shift.create'), 'create_label' => __('Add Shift')])

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table si-datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Start Time') }}</th>
                                <th>{{ __('End Time') }}</th>
                                <th>{{ __('Active Days') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th class="no-sort">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shifts as $shift)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $shift->nama }}</td>
                                    <td>{{ $shift->waktu_mulai }}</td>
                                    <td>{{ $shift->waktu_selesai }}</td>
                                    <td>
                                        @php
                                            $days = [];
                                            if ($shift->senin) $days[] = __('Monday');
                                            if ($shift->selasa) $days[] = __('Tuesday');
                                            if ($shift->rabu) $days[] = __('Wednesday');
                                            if ($shift->kamis) $days[] = __('Thursday');
                                            if ($shift->jumat) $days[] = __('Friday');
                                            if ($shift->sabtu) $days[] = __('Saturday');
                                            if ($shift->minggu) $days[] = __('Sunday');
                                        @endphp
                                        {{ implode(', ', $days) ?: __('None') }}
                                    </td>
                                    <td>{{ $shift->description ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('shift.edit', $shift->id) }}" class="btn btn-sm btn-warning">{{ __('Edit') }}</a>
                                        <form action="{{ route('shift.destroy', $shift->id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('{{ __('Are you sure you want to delete this data?') }}');">{{ __('Delete') }}</button>
                                        </form>
                                        <a href="{{ route('shift.assignForm', $shift->id) }}" class="btn btn-sm btn-primary">{{ __('Assign Employees') }}</a>
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
