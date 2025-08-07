<div class="modal fade" id="addPaymentModal{{ $vendor->id }}" tabindex="-1" aria-labelledby="addPaymentModalLabel{{ $vendor->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPaymentModalLabel{{ $vendor->id }}">Add Payment for {{ $vendor->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.vendor.payments.store', $vendor->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="amount{{ $vendor->id }}" class="form-label">Amount</label>
                        <input type="number" step="0.01" name="amount" id="amount{{ $vendor->id }}" class="form-control" required max="{{ $vendor->total_remaining_amount }}">
                        <div class="form-text">Max: {{ number_format($vendor->total_remaining_amount, 2) }}</div>
                    </div>
                    <div class="mb-3">
                        <label for="payment_date{{ $vendor->id }}" class="form-label">Payment Date</label>
                        <input type="date" name="payment_date" id="payment_date{{ $vendor->id }}" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="description{{ $vendor->id }}" class="form-label">Description</label>
                        <textarea name="description" id="description{{ $vendor->id }}" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="payment_mode{{ $vendor->id }}" class="form-label">Payment Mode</label>
                        <select name="payment_mode" id="payment_mode{{ $vendor->id }}" class="form-control payment-mode-select" required onchange="filterAccountsByPaymentMode(this, '{{ $vendor->id }}')">
                            <option value="">Select payment mode</option>
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="account_id{{ $vendor->id }}" class="form-label">Account</label>
                        <select name="account_id" id="account-select-{{ $vendor->id }}" class="form-control" required>
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