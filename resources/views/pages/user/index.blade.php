@extends('layouts.app')

@section('content')
    <x-page :title="__('Employees')">

        @include('layouts._toolbar', ['tabs' => 'user_cluster', 'create_url' => route('user.create'), 'create_label' => __('Add Employee')])

        <div class="card">
            <div class="card-body">

                {{-- serverSide table: rows come from the ajax endpoint, so the body stays empty --}}
                <table class="table" id="datatable">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('E-mail') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <!-- /.card -->

    </x-page>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                language: window.SISDM.dtLang,
                ajax: '{{ url('user') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush
