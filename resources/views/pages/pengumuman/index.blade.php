@extends('layouts.app')

@section('content')
    <x-page :title="__('Announcements')">

        @include('layouts._toolbar', ['create_url' => route('pengumuman.create'), 'create_label' => __('Add Announcement')])

        <div class="card">
            <div class="card-body">
                <table class="table si-datatable">
                    <thead>
                        <tr>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Message') }}</th>
                            <th>{{ __('Photo') }}</th>
                            <th class="no-sort">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengumuman as $item)
                            <tr>
                                <td>{{ $item->judul }}</td>
                                <td>{{ $item->pesan }}</td>
                                <td>
                                    @if ($item->foto)
                                        <img src="{{ asset('storage/' . $item->foto) }}" alt="foto" width="100">
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('pengumuman.edit', $item) }}" class="btn btn-sm btn-warning">{{ __('Edit') }}</a>
                                    <form action="{{ route('pengumuman.destroy', $item) }}" method="POST" style="display:inline;">
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
