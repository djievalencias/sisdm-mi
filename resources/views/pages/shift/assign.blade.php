@extends('layouts.app')

@section('content')
    <x-page :title="__('Assign Employees to Shift') . ': ' . $shift->nama" :breadcrumb="__('Assign Employees')">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Select Employees') }}</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('shift.assign', $shift->id) }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        {{-- plain table (no si-datatable): DataTables pagination removes off-page
                             rows from the DOM, which would drop their checkboxes from the submit --}}
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('Select') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('E-mail') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="users[]" value="{{ $user->id }}"
                                                {{ in_array($user->id, $assignedUsers) ? 'checked' : '' }}>
                                        </td>
                                        <td>{{ $user->nama }}</td>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <button type="submit" class="btn btn-success">{{ __('Assign Employees') }}</button>
                    <a href="{{ route('shift.index') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left mr-1"></i> {{ __('Back') }}</a>
                </form>
            </div>
        </div>

    </x-page>
@endsection
