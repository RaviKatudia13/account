@extends('layouts.admin')

@section('title', 'employee_due')

@section('content')
<div class="container mt-16">
<h1 class="h3 mb-4 fw-bold">Employee Due</h1>
<!-- Add Due Button -->
 
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addDueModal">
    <i class="fa fa-plus"></i> Add Due
</button>

<!-- Add Due Modal -->
<div class="modal fade" id="addDueModal" tabindex="-1" aria-labelledby="addDueModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDueModalLabel">Add Employee Due</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.employee-due.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="employeeName" class="form-label">Employee Name</label>
                        <select class="form-control" id="employeeName" name="employee_id">
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="dueDate" class="form-label">Date</label>
                        <input type="date" class="form-control" id="dueDate" name="date">
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Total Amount</label>
                        <input type="number" step="0.01" class="form-control" id="amount" name="total_amount" placeholder="Enter total amount">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="2" placeholder="Enter description"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Due</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Employee Due Table -->
<div class="table-responsive">
<table class="table responsive-table table-bordered mt-4">
    <thead>
        <tr>
            <th>Employee</th>
            <th>Date</th>
            <th>Total Amount</th>
            <th>Paid</th>
            <th>Remaining</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($employeeDues as $due)
            <tr>
                <td>{{ $due->employee->name ?? '-' }}</td>
                <td>{{ $due->date }}</td>
                <td>{{ number_format($due->total_amount, 2) }}</td>
                <td>{{ number_format($due->paid_amount, 2) }}</td>
                <td>{{ number_format($due->remaining_amount, 2) }}</td>
                <td>{{ $due->description ?? '' }}</td>
            </tr>
            <!-- Payment Modal -->
            @if($due->remaining_amount > 0)
            <div class="modal fade" id="payEmployeeDueModal-{{ $due->id }}" tabindex="-1" aria-labelledby="payEmployeeDueModalLabel-{{ $due->id }}" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form action="{{ route('admin.employee-due.pay', $due->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                      <h5 class="modal-title" id="payEmployeeDueModalLabel-{{ $due->id }}">Record Payment for {{ $due->employee->name }}</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-3">
                        <label for="amount-{{ $due->id }}" class="form-label">Amount</label>
                        <input type="number" class="form-control" name="amount" id="amount-{{ $due->id }}" max="{{ $due->remaining_amount }}" min="1" required>
                      </div>
                      <div class="mb-3">
                        <label for="payment_date-{{ $due->id }}" class="form-label">Payment Date</label>
                        <input type="date" class="form-control" name="payment_date" id="payment_date-{{ $due->id }}" value="{{ date('Y-m-d') }}" required>
                      </div>
                      <div class="mb-3">
                        <label for="payment_mode-{{ $due->id }}" class="form-label">Payment Mode</label>
                        <select class="form-control" name="payment_mode" id="payment_mode-{{ $due->id }}" required>
                          <option value="Cash">Cash</option>
                          <option value="Bank">Bank</option>
                          <option value="Other">Other</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="account_id-{{ $due->id }}" class="form-label">Account</label>
                        <select class="form-control" name="account_id" id="account_id-{{ $due->id }}" required>
                          <option value="">Select Account</option>
                          @foreach($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="description-{{ $due->id }}" class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="description-{{ $due->id }}"></textarea>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      <button type="submit" class="btn btn-primary">Record Payment</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            @endif
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4" class="text-end">Total Remaining Due</th>
            <th>{{ number_format($employeeDues->sum('remaining_amount'), 2) }}</th>
            <th colspan="2"></th>
        </tr>
    </tfoot>
</table>
</div>
</div>


@endsection

@section('scripts')
<script>
    const employeeSalary = {
        @foreach ($employees as $employee)
            {{ $employee->id }}: {{ (float) $employee->salary }},
        @endforeach
    };
    document.addEventListener('DOMContentLoaded', function() {
        const employeeSelect = document.getElementById('employeeName');
        const paidInput = document.getElementById('paidAmount');
        const maxHint = document.getElementById('max-hint');
        employeeSelect.addEventListener('change', function() {
            const employeeId = this.value;
            if (employeeId && employeeSalary[employeeId] !== undefined) {
                paidInput.max = employeeSalary[employeeId];
                maxHint.textContent = 'Max: ' + Number(employeeSalary[employeeId]).toFixed(2);
            } else {
                paidInput.max = '';
                maxHint.textContent = '';
            }
        });

        // Filter accounts based on payment mode selection
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.payment-mode-select').forEach(function(paymentModeSelect) {
                const modal = paymentModeSelect.closest('.modal');
                const accountSelect = modal.querySelector('.account-select');
                
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
        });
    });
</script>
@endsection