@extends('layouts.admin')

@section('title', 'Payments')

@section('content')
<div class="container mt-16">

<div class="mb-4">
    <h1 class="h3 fw-bold">Payments</h1>
    <p class="text-muted">All recorded payments</p>
    <div class="bg-white rounded shadow-sm border p-4">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Credit/Debit</th>
                        <th>Client / Vendor / Employee</th>
                        <th>Amount</th>
                        <th>Payment Date</th>
                        <th>Payment Mode</th>
                        <th>Account</th>
                        <th>Internal Transfer</th>
                        <th>Recorded By</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr>
                        <td>
                          
                        {{ $payment->id }}
                        </td>
                        <td>
                            {{ $payment->type == 1 ? 'Credit' : ($payment->type == 2 ? 'Debit' : $payment->type) }}
                        </td>
                        <td>
                        @if($payment->vendor_id)
                                {{ $payment->vendor->name ?? '-' }}
                            @elseif($payment->employee_id)
                                {{ $payment->employee->name ?? '-' }}
                            @elseif($payment->invoice_id)
                                {{ $payment->invoice->client->name ?? '-' }}
                            @elseif($payment->subscription_client_payment_id)
                                {{ $payment->subscriptionClientPayment && $payment->subscriptionClientPayment->client ? $payment->subscriptionClientPayment->client->name : '-' }}
                            @else
                                -
                            @endif
                        </td>
                        <td>₹{{ number_format($payment->amount, 2) }}</td>
                        <td>{{ $payment->payment_date }}</td>
                        <td>{{ $payment->paymentMethod->name ?? '-' }}</td>
                        <td>
                            @if($payment->account && is_object($payment->account))
                                {{ $payment->account->display_name }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($payment->internal_transfer)
                                <span class="badge bg-info">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                        <td>{{ $payment->recordedByUser->name ?? $payment->recorded_by }}</td>
                        <td>{{ $payment->remarks ?: '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@endsection 