@extends('layouts.app')

@section('content')
    <h1>Payroll List</h1>

    <a href="{{ route('payroll.create') }}" class="btn btn-primary mb-3">Create New Payroll</a>

    @if($payrolls->count())
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Payroll Date</th>
                    <th>Take Home Pay</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payrolls as $payroll)
                    <tr>
                        <td>{{ $payroll->user->nama }}</td>
                        <td>{{ $payroll->tanggal_payroll }}</td>
                        <td>{{ number_format($payroll->take_home_pay, 2) }}</td>
                        <td>
                            <a href="{{ route('payroll.show', $payroll->id) }}" class="btn btn-info btn-sm">Details</a>
                            <a href="{{ route('payroll.edit', $payroll->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            
                            <!-- Delete button -->
                            <form action="{{ route('payroll.destroy', $payroll->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this payroll record?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No payroll records found. <a href="{{ route('payroll.create') }}">Create a new payroll</a>.</p>
    @endif
@endsection
