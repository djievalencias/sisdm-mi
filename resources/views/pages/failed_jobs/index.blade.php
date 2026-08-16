@extends('layouts.app')

@section('content')
    <x-page :title="__('Failed Jobs')">

        @if ($failedJobs->isNotEmpty())
            <div class="d-flex justify-content-end mb-3" style="gap: .5rem;">
                <form action="{{ route('failed-jobs.retry-all') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success">{{ __('Retry All') }}</button>
                </form>
                <form action="{{ route('failed-jobs.flush') }}" method="POST" class="d-inline"
                    onsubmit="return confirm('{{ __('Are you sure you want to delete this data?') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">{{ __('Clear All') }}</button>
                </form>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                @if ($failedJobs->isEmpty())
                    <p class="text-muted mb-0">{{ __('No failed jobs — the queue is healthy.') }}</p>
                @else
                    <div class="table-responsive">
                        <table class="table si-datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('Failed At') }}</th>
                                    <th>{{ __('Job') }}</th>
                                    <th>{{ __('Queue') }}</th>
                                    <th>{{ __('Error') }}</th>
                                    <th class="no-sort">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($failedJobs as $job)
                                    <tr>
                                        <td>{{ $job->failed_at }}</td>
                                        <td>{{ $job->job_name }}</td>
                                        <td>{{ $job->connection }} / {{ $job->queue }}</td>
                                        <td><code>{{ Str::limit($job->error_summary, 140) }}</code></td>
                                        <td>
                                            <form action="{{ route('failed-jobs.retry', $job->uuid) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">{{ __('Retry') }}</button>
                                            </form>
                                            <form action="{{ route('failed-jobs.destroy', $job->uuid) }}" method="POST" class="d-inline"
                                                onsubmit="return confirm('{{ __('Are you sure you want to delete this data?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">{{ __('Delete') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </x-page>
@endsection
