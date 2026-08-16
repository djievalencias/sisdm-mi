@extends('layouts.app')

@section('content')
    <x-page :title="__('Offices')">

        @include('layouts._toolbar', ['tabs' => 'user_cluster', 'create_url' => route('kantor.create'), 'create_label' => __('Add Office')])

        <div class="card">
            <div class="card-body">
                <table class="table si-datatable">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Address') }}</th>
                            <th>{{ __('Coordinates') }}</th>
                            <th>{{ __('Radius') }}</th>
                            <th>{{ __('Manager') }}</th>
                            <th class="no-sort">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kantor as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->alamat }}</td>
                                <td>{{ $item->koordinat_x }}, {{ $item->koordinat_y }}</td>
                                <td>{{ $item->radius }}</td>
                                <td>{{ $item->manager->nama ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('kantor.edit', $item->id) }}"
                                        class="btn btn-sm btn-warning">{{ __('Edit') }}</a>
                                    <form action="{{ route('kantor.destroy', $item->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
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
