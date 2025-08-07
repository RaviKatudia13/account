@extends('layouts.admin')

@section('title', 'Accounts')

@section('content')
<div class="container mt-16">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0 fw-bold">Accounts</h1>
        <a href="{{ route('admin.accounts.create') }}" class="btn btn-primary">+ Add Account</a>
    </div>

    <div class="card">
        <div class="card-body responsive-table">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Payment Mode</th>
                            <th>Account Number/UPI ID</th>
                            <th>Bank Name</th>
                            <th>Holder Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accounts as $account)
                        <tr>
                            <td>{{ $account->name }}</td>
                            <td>
                                <span class="badge bg-{{ $account->type === 'bank' ? 'primary' : ($account->type === 'upi' ? 'success' : 'warning') }}">
                                    {{ ucfirst($account->type) }}
                                </span>
                            </td>
                            <td>{{ $account->paymentMethod->name ?? '-' }}</td>
                            <td>
                                @if($account->type === 'bank')
                                    {{ $account->account_number ?? '-' }}
                                @elseif($account->type === 'upi')
                                    {{ $account->upi_id ?? '-' }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $account->bank_name ?? '-' }}</td>
                            <td>{{ $account->holder_name ?? '-' }}</td>
                            <td>
                                @if($account->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.accounts.edit', $account) }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.accounts.destroy', $account) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this account?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 