@extends('layouts.app')

@section('content')
    <x-page :title="__('Positions')">

        @include('layouts._toolbar', ['tabs' => 'user_cluster', 'create_url' => route('jabatan.create'), 'create_label' => __('Add Position')])

        <div class="card">
            <div class="card-body">
                <table class="table si-datatable">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Group') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th class="no-sort">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jabatan as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->grup->nama ?? '-' }}</td>
                                <td>{{ $item->description ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('jabatan.edit', $item->id) }}" class="btn btn-sm btn-warning">{{ __('Edit') }}</a>
                                    <form action="{{ route('jabatan.destroy', $item->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('Are you sure you want to delete this data?') }}')">{{ __('Delete') }}</button>
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
