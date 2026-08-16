@extends('layouts.app')

@section('content')
    <x-page :title="__('Archived Employees')">

        @include('layouts._toolbar', ['tabs' => 'user_cluster'])

        <div class="card">
            <div class="card-body">
                <table class="table si-datatable">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('E-mail') }}</th>
                            <th class="no-sort">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->nama }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @include('layouts._action', [
                                        'restore_url' => route('user.restore', $user->id),
                                        'delete_url' => route('user.destroy', $user->id),
                                        'show_url' => null, 'edit_url' => null, 'archive_url' => null,
                                    ])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </x-page>
@endsection
