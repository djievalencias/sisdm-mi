@extends('layouts.app')

@section('content')
    <x-page :title="__('Event Details')" :breadcrumb="__('Work Calendar')">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $kalender->judul }}</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <th>{{ __('Start Date') }}</th>
                            <td>{{ $kalender->tanggal_mulai }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('End Date') }}</th>
                            <td>{{ $kalender->tanggal_selesai ?? __('Not specified') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Type') }}</th>
                            <td>{{ __(ucfirst(str_replace('_', ' ', $kalender->tipe))) }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Created By') }}</th>
                            <td>{{ $kalender->createdBy->nama ?? __('Unknown') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Last Updated By') }}</th>
                            <td>{{ $kalender->updatedBy->nama ?? __('Unknown') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Created At') }}</th>
                            <td>{{ $kalender->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Last Updated') }}</th>
                            <td>{{ $kalender->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('kalender.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> {{ __('Back') }}</a>
                <a href="{{ route('kalender.edit', $kalender->id) }}" class="btn btn-warning">{{ __('Edit') }}</a>
                <form action="{{ route('kalender.destroy', $kalender->id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('{{ __('Are you sure you want to delete this data?') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                </form>
            </div>
        </div>
    </x-page>
@endsection
