<div class="modal fade" id="addPaymentModal{{ $employee->id }}" tabindex="-1" aria-labelledby="addPaymentModalLabel{{ $employee->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPaymentModalLabel{{ $employee->id }}">Add Payment for {{ $employee->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.employee.payments.store', $employee->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="amount{{ $employee->id }}" class="form-label">Amount</label>
                        <input type="number" step="0.01" name="amount" id="amount{{ $employee->id }}" class="form-control" required max="{{ $employee->total_remaining_amount }}">
                        <div class="form-text">Max: {{ number_format($employee->total_remaining_amount, 2) }}</div>
                    </div>
                    <div class="mb-3">
                        <label for="payment_date{{ $employee->id }}" class="form-label">Payment Date</label>
                        <input type="date" name="payment_date" id="payment_date{{ $employee->id }}" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="description{{ $employee->id }}" class="form-label">Description</label>
                        <textarea name="description" id="description{{ $employee->id }}" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="payment_mode{{ $employee->id }}" class="form-label">Payment Mode</label>
                        <select name="payment_mode" id="payment_mode{{ $employee->id }}" class="form-control payment-mode-select" required onchange="filterAccountsByPaymentMode(this, '{{ $employee->id }}')">
                            <option value="">Select payment mode</option>
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="account_id{{ $employee->id }}" class="form-label">Account</label>
                        <select name="account_id" id="account-select-{{ $employee->id }}" class="form-control" required>
                            <option value="">Select account</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}" data-payment-mode="{{ $account->payment_mode_id }}">{{ $account->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function filterAccountsByPaymentMode(select, id) {
    var mode = select.value;
    var accountSelect = document.getElementById('account-select-' + id);
    Array.from(accountSelect.options).forEach(function(option) {
        if (!option.value) { option.style.display = ''; return; }
        option.style.display = (option.getAttribute('data-payment-mode') === mode) ? '' : 'none';
    });
    accountSelect.value = '';
}
</script> 