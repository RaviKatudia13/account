@extends('layouts.admin')

@section('title', 'Internal Transfer List')

@section('content')
@php
    $totalAmount = $transfers->sum('amount');
    // Debug: Show account count
    echo "<!-- Debug: Total accounts passed to view: " . $accounts->count() . " -->";
@endphp
<div class="container mt-16">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0 fw-bold">Internal Transfer List</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTransferModal">+ Add Internal Transfer</button>
    </div>
    
    
    <!-- Add Internal Transfer Modal -->
    <div class="modal fade" id="addTransferModal" tabindex="-1" aria-labelledby="addTransferModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTransferModalLabel">Add Internal Transfer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.internal-transfer.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="debit_account" class="form-label">Debit Account</label>
                            <select class="form-control" id="debit_account" name="debit_account" required>
                                <option value="">Select Debit Account</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->name }}">{{ $account->display_name }}</option>
                                @endforeach
                            </select>
                            <!-- Debug: {{ $accounts->count() }} accounts available -->
                            <!-- Debug accounts: @foreach($accounts as $acc) {{ $acc->name }}, @endforeach -->
                            @error('debit_account')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="credit_account" class="form-label">Credit Account</label>
                            <select class="form-control" id="credit_account" name="credit_account" required>
                                <option value="">Select Credit Account</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->name }}">{{ $account->display_name }}</option>
                                @endforeach
                            </select>
                            <!-- Debug: {{ $accounts->count() }} accounts available -->
                            <!-- Debug accounts: @foreach($accounts as $acc) {{ $acc->name }}, @endforeach -->
                            @error('credit_account')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" required>
                            @error('amount')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="2">{{ old('description') }}</textarea>
                            @error('description')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Transfer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body responsive-table">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Account</th>
                        <th>Total Credit</th>
                        <th>Total Debit</th>
                        <th>Balance</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accounts as $account)
                        @php
                            $totalCredit = $transfers->where('credit_account', $account->name)->sum('amount');
                            $totalDebit = $transfers->where('debit_account', $account->name)->sum('amount');
                            $balance = $totalCredit - $totalDebit;
                        @endphp
                        <tr>
                            <td>{{ $account->display_name }}</td>
                            <td class="text-success">₹{{ number_format($totalCredit, 2) }}</td>
                            <td class="text-danger">₹{{ number_format($totalDebit, 2) }}</td>
                            <td class="{{ $balance >= 0 ? 'text-success' : 'text-danger' }}">
                                ₹{{ number_format($balance, 2) }}
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#historyModal{{ md5($account->name) }}">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                <strong>Total Amount:</strong> ₹{{ number_format($totalAmount, 2) }}
            </div>
        </div>
    </div>
</div>
<!-- Render all modals after the table -->
@foreach($accounts as $account)
<div class="modal fade" id="historyModal{{ md5($account->name) }}" tabindex="-1" aria-labelledby="historyModalLabel{{ md5($account->name) }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historyModalLabel{{ md5($account->name) }}">History for {{ $account->display_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Debit/Credit</th>
                            <th>Counterparty</th>
                            <th>Amount</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $hasHistory = false; @endphp
                        @foreach($transfers as $t)
                            @if($t->debit_account === $account->name || $t->credit_account === $account->name)
                                @php $hasHistory = true; @endphp
                                <tr>
                                    <td>{{ $t->created_at ? $t->created_at->format('Y-m-d') : '-' }}</td>
                                    <td>
                                        @if($t->debit_account === $account->name)
                                            <span class="text-danger">Debit</span>
                                        @else
                                            <span class="text-success">Credit</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($t->debit_account === $account->name)
                                            {{ $t->credit_account }}
                                        @else
                                            {{ $t->debit_account }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($t->debit_account === $account->name)
                                            -₹{{ number_format($t->amount, 2) }}
                                        @else
                                            +₹{{ number_format($t->amount, 2) }}
                                        @endif
                                    </td>
                                    <td>{{ $t->description }}</td>
                                </tr>
                            @endif
                        @endforeach
                        @if(!$hasHistory)
                            <tr>
                                <td colspan="5" class="text-center">No transactions for this account.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection 