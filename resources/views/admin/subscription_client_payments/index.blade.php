@extends('layouts.admin')

@section('content')
<div class="container mt-16">
    <h1 class="h3 mb-4 fw-bold">Subscription Client Payments</h1>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
        Add Payment
    </button>
    <div class="card">
        <div class="card-body responsive-table">
        <table class="table table-bordered table-striped">
            <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Client</th>
                        <th scope="col">Invoice #</th>
                        <th scope="col">Start Date</th>
                        <th scope="col">End Date</th>
                        <th scope="col">Total</th>
                        <th scope="col">Paid Amount</th>
                        <th scope="col">Remain Amount</th>
                        <th scope="col">Status</th>
                        <th scope="col">Description</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($payments) && count($payments))
                        @foreach($payments as $payment)
                        <tr>
                            <td>{{ $payment->id ?? '-' }}</td>
                            <td>
                                {{ $payment->client && $payment->client->name ? $payment->client->name : '-' }}
                            </td>
                            <td>{{ $payment->invoice_number ?? '-' }}</td>
                            <td>{{ $payment->start_date ? \Carbon\Carbon::parse($payment->start_date)->format('d-M-Y') : '-' }}</td>
                            <td>{{ $payment->end_date ? \Carbon\Carbon::parse($payment->end_date)->format('d-M-Y') : '-' }}</td>
                            <td>
                                {{ is_numeric($payment->total) ? number_format($payment->total, 2) : '-' }}
                            </td>
                            <td>{{ is_numeric($payment->paid_amount) ? number_format($payment->paid_amount, 2) : '0.00' }}</td>
                            <td>{{ (is_numeric($payment->total) && is_numeric($payment->paid_amount)) ? number_format($payment->total - $payment->paid_amount, 2) : '0.00' }}</td>
                            <td>
                                @if($payment->status === 'Paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($payment->status === 'Partial')
                                    <span class="badge bg-warning text-dark">Partial</span>
                                @elseif($payment->status === 'Due')
                                    <span class="badge bg-danger">Due</span>
                                @else
                                    <span class="badge bg-secondary">{{ $payment->status ?? '-' }}</span>
                                @endif
                            </td>
                            <td>{{ $payment->description ?? '-' }}</td>
                            <td>
                                <div class="table-action-btn-group">
                                    <button class="btn btn-sm btn-primary" title="View Payment" data-bs-toggle="modal" data-bs-target="#viewPaymentModal{{ $payment->id }}">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    @if(($payment->total - $payment->paid_amount) > 0)
                                        <button class="btn btn-sm btn-success" title="Do Payment" data-bs-toggle="modal" data-bs-target="#payPaymentModal{{ $payment->id }}">
                                            <i class="fa fa-credit-card"></i>
                                        </button>
                                    @endif
                                    <form action="#" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')" title="Delete Payment">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="12" class="text-center">No payments found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            {{-- Render all view modals after the table for proper Bootstrap behavior --}}
            @if(isset($payments))
                <div class="d-flex justify-content-center mt-3">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
{{-- Add Payment Modal --}}
@include('admin.subscription_client_payments.modals.create-invoice')
{{-- Do Payment Modal --}}
@if(isset($payments) && count($payments))
    @foreach($payments as $payment)
        @include('admin.subscription_client_payments.modals.view-details-invoice', ['payment' => $payment])
        @include('admin.subscription_client_payments.modals.pay-invoice', ['payment' => $payment])
    @endforeach
@endif
@endsection 