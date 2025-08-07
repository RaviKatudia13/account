{{-- resources/views/invoices/modals/_pay-invoice-body.blade.php --}}
<h2 class="text-lg font-semibold mb-1">Record Payment</h2>

<form class="space-y-4 text-sm" method="POST" action="{{ route('admin.subscription-client-payments.record-payment', $payment->id) }}">
    @csrf
    @php
        $remainAmount = (is_numeric($payment->total) && is_numeric($payment->paid_amount))
            ? $payment->total - $payment->paid_amount
            : 0;
    @endphp
    <div>
        <label class="block text-sm font-medium mb-1">Amount (₹)</label>
        <input
            type="number"
            name="amount"
            placeholder="Enter amount"
            class="w-full border rounded px-3 py-2 text-sm"
            max="{{ $remainAmount }}"
            step="0.01"
            required
            oninput="validateMaxAmount(this, {{ $remainAmount }})"
        />
        <div class="text-xs text-gray-500 mt-1">
            Max allowed: ₹{{ number_format($remainAmount, 2) }}
        </div>
        <div id="amount-error-{{ $payment->id }}" class="text-xs text-danger mt-1" style="display:none;">
            Amount cannot exceed max allowed.
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Payment Date</label>
        <input type="date" name="payment_date" value="{{ now()->format('Y-m-d') }}" class="w-full border rounded px-3 py-2 text-sm" required />
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Payment Mode</label>
        <select name="payment_mode_id" class="w-full border rounded px-3 py-2 text-sm payment-mode-select" required onchange="filterAccountsByPaymentMode(this, '{{ $payment->id }}')">
            <option value="">Select payment mode</option>
            @foreach($paymentMethods as $method)
                <option value="{{ $method->id }}">{{ $method->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Account</label>
        <select name="account_id" id="account-select-{{ $payment->id }}" class="w-full border rounded px-3 py-2 text-sm" required>
            <option value="">Select account</option>
            @foreach($accounts as $account)
                <option value="{{ $account->id }}" data-payment-mode="{{ $account->payment_mode_id }}">{{ $account->display_name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Remarks</label>
        <textarea name="remarks" rows="3" placeholder="Enter additional details (optional)" class="w-full border rounded px-3 py-2 text-sm"></textarea>
    </div>
    <div class="flex justify-end gap-2">
        <button type="button" class="px-4 py-2 border rounded-md text-sm" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">Record Payment</button>
    </div>
</form>

<script>
function validateMaxAmount(input, max) {
    const errorDiv = document.getElementById('amount-error-{{ $payment->id }}');
    if (parseFloat(input.value) > max) {
        input.value = max;
        errorDiv.style.display = 'block';
    } else {
        errorDiv.style.display = 'none';
    }
}

function filterAccountsByPaymentMode(select, paymentId) {
    var mode = select.value;
    var accountSelect = document.getElementById('account-select-' + paymentId);
    Array.from(accountSelect.options).forEach(function(option) {
        if (!option.value) { option.style.display = ''; return; }
        option.style.display = (option.getAttribute('data-payment-mode') === mode) ? '' : 'none';
    });
    accountSelect.value = '';
}
</script>
