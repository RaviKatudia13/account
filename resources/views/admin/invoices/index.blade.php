{{-- resources/views/invoices/index.blade.php --}}
@extends('layouts.admin')

@push('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('title', 'Invoices')

@section('content')
<!-- Debug Information -->
<!-- <div class="alert alert-info">
    <strong>Debug Info:</strong><br>
    Total Invoices: {{ $invoices->count() }}<br>
    Invoices Collection: {{ $invoices->toJson() }}
</div> -->

<div class="mb-4 mt-16">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold">Invoices</h1>
            <p class="text-muted">Manage your invoice billing and payments</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-users"></i> View Clients
            </a>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addInvoiceModal">
                <i class="fas fa-plus"></i> New Invoice
            </button>
        </div>
    </div>

    <div class="bg-white rounded shadow-sm border p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <input type="text" placeholder="🔍 Search invoices..." class="form-control w-50">
            <div class="btn-group">
                <button class="btn btn-sm btn-outline-secondary">All</button>
                <button class="btn btn-sm btn-outline-secondary">Paid</button>
                <button class="btn btn-sm btn-outline-secondary">Due</button>
            </div>
        </div>
        <div class="table-responsive responsive-table">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Invoice No</th>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Total Amount</th>
                        <th>Paid Amount</th>
                        <th>Remaining</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $inv)
                    @php
                        $totalPaid = $inv->payments()->sum('amount');
                        $remainingAmount = $inv->total - $totalPaid;
                    @endphp
                    <tr>
                        <td class="fw-semibold">
                            {{ str_pad($inv->id, 7, '2025', STR_PAD_LEFT) }}
                        </td>
                        <td><i class="far fa-calendar-alt text-gray-400"></i> {{ \Carbon\Carbon::parse($inv->invoice_date)->format('d/m/Y') }}</td>
                        <td>{{ $inv->client ? ($inv->client->name ?? $inv->client->name) : 'No Client' }}</td>
                        <td>₹{{ number_format($inv->total, 2) }}</td>
                        <td>₹{{ number_format($totalPaid, 2) }}</td>
                        <td>
                            @if($remainingAmount > 0)
                                <span class="text-danger fw-semibold">₹{{ number_format($remainingAmount, 2) }}</span>
                            @else
                                <span class="text-success fw-semibold">₹0.00</span>
                            @endif
                        </td>
                        <td>
                            @if($inv->status=='Paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($inv->status=='Partial')
                                <span class="badge bg-warning text-dark">Partial</span>
                            @elseif($inv->status=='Due')
                                <span class="badge bg-danger">Due</span>
                            @else
                                <span class="badge bg-secondary">{{ $inv->status }}</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#viewInvoiceModal{{ $inv->id }}"><i class="fa-solid fa-eye"></i></button>
                            @if($remainingAmount > 0)
                            <button class="btn btn-sm btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#payInvoiceModal{{ $inv->id }}"><i class="fa-solid fa-money-check-alt"></i></button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- View Invoice Modals -->
    @foreach ($invoices as $inv)
    @php
        $totalPaid = $inv->payments()->sum('amount');
        $remainingAmount = $inv->total - $totalPaid;
    @endphp
    <div class="modal fade" id="viewInvoiceModal{{ $inv->id }}" tabindex="-1" aria-labelledby="viewInvoiceModalLabel{{ $inv->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewInvoiceModalLabel{{ $inv->id }}">Invoice Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Invoice Information</h6>
                            <p><strong>Invoice No:</strong> {{ $inv->invoice_number }}</p>
                            <p><strong>Client:</strong> {{ $inv->client ? ($inv->client->name ?? $inv->client->name) : 'No Client' }}</p>
                            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($inv->invoice_date)->format('d/m/Y') }}</p>
                            <p><strong>Total Amount:</strong> ₹{{ number_format($inv->total, 2) }}</p>
                            <p><strong>Status:</strong> 
                                @if($inv->status=='Paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($inv->status=='Partial')
                                    <span class="badge bg-warning text-dark">Partial</span>
                                @elseif($inv->status=='Due')
                                    <span class="badge bg-danger">Due</span>
                                @else
                                    <span class="badge bg-secondary">{{ $inv->status }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Payment Summary</h6>
                            <p><strong>Total Paid:</strong> ₹{{ number_format($totalPaid, 2) }}</p>
                            <p><strong>Remaining Balance:</strong> 
                                @if($remainingAmount > 0)
                                    <span class="text-danger fw-semibold">₹{{ number_format($remainingAmount, 2) }}</span>
                                @else
                                    <span class="text-success fw-semibold">₹0.00</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    @if($inv->payments->count() > 0)
                    <div class="mt-4">
                        <h6>Payment History</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Mode</th>
                                        <th>Recorded By</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inv->payments as $payment)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                                        <td>₹{{ number_format($payment->amount, 2) }}</td>
                                        <td>{{ $payment->paymentMethod->name ?? '-' }}</td>
                                        <td>{{ optional($payment->recordedByUser)->name ?? '-' }}</td>
                                        <td>{{ $payment->remarks ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Pay Invoice Modals -->
    @foreach ($invoices as $inv)
    @php
        $totalPaid = $inv->payments()->sum('amount');
        $remainingAmount = $inv->total - $totalPaid;
    @endphp
    <div class="modal fade" id="payInvoiceModal{{ $inv->id }}" tabindex="-1" aria-labelledby="payInvoiceModalLabel{{ $inv->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="payInvoiceModalLabel{{ $inv->id }}">Record Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Invoice Total:</strong> ₹{{ number_format($inv->total, 2) }}<br>
                        <strong>Already Paid:</strong> ₹{{ number_format($totalPaid, 2) }}<br>
                        <strong>Remaining Balance:</strong> ₹{{ number_format($remainingAmount, 2) }}
                    </div>
                    <form method="POST" action="{{ route('admin.invoices.pay', $inv->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Payment Amount (₹)</label>
                            <input type="number" name="amount" class="form-control" step="0.01" max="{{ $remainingAmount }}" required>
                            <div class="form-text">Maximum payment amount: ₹{{ number_format($remainingAmount, 2) }}</div>
                            @error('amount')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Date</label>
                            <input type="date" name="payment_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Mode</label>
                            <select name="payment_mode" id="payment_mode" class="form-select" required>
                                <option value="">Select payment mode</option>
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method->id }}">{{ $method->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Account</label>
                            <select name="account_id" id="account_select" class="form-select" required>
                                <option value="">Select account</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" data-payment-mode="{{ $account->payment_mode_id }}">{{ $account->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" rows="3" class="form-control" placeholder="Enter additional details (optional)"></textarea>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Record Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Payments Table (below the invoices table) -->
    <!-- @if(isset($payments) && count($payments))
    <div class="mt-5">
        <h4>Payments</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Invoice No</th>
                        <th>Client</th>
                        <th>Amount</th>
                        <th>Payment Date</th>
                        <th>Payment Mode</th>
                        <th>Account</th>
                        <th>Recorded By</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr>
                        <td>{{ $payment->invoice->invoice_number ?? '' }}</td>
                        <td>{{ $payment->invoice && $payment->invoice->client ? ($payment->invoice->client->company_name ?? $payment->invoice->client->name) : 'No Client' }}</td>
                        <td>₹{{ number_format($payment->amount, 2) }}</td>
                        <td>{{ $payment->payment_date }}</td>
                        <td>{{ $payment->payment_mode }}</td>
                        <td>{{ $payment->account }}</td>
                        <td>{{ $payment->recorded_by }}</td>
                        <td>{{ $payment->remarks ?: '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif -->

    <!-- Add Invoice Modal -->
    <div class="modal fade" id="addInvoiceModal" tabindex="-1" aria-labelledby="addInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addInvoiceModalLabel">Create New Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('admin.invoices.modals._create-invoice-body')
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Filter accounts based on payment mode selection
document.addEventListener('DOMContentLoaded', function() {
    const paymentModeSelect = document.getElementById('payment_mode');
    const accountSelect = document.getElementById('account_select');
    
    if (paymentModeSelect && accountSelect) {
        paymentModeSelect.addEventListener('change', function() {
            const selectedPaymentMode = this.value;
            const accountOptions = accountSelect.querySelectorAll('option');
            
            // Reset account selection
            accountSelect.value = '';
            
            // Show/hide account options based on payment mode
            accountOptions.forEach(option => {
                if (option.value === '') {
                    // Keep the "Select account" option always visible
                    option.style.display = 'block';
                } else {
                    const accountPaymentMode = option.getAttribute('data-payment-mode');
                    if (selectedPaymentMode === '' || accountPaymentMode === selectedPaymentMode) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                }
            });
        });
    }
});
</script>
@endsection
