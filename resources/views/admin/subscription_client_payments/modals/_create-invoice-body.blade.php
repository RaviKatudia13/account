<h2 class="text-lg font-semibold mb-1">Create New Subscription Client Payment</h2>
<p class="text-sm text-gray-500 mb-4">Create a new subscription client payment for your client.</p>
<form class="space-y-4" method="POST" action="{{ route('admin.subscription-client-payments.store') }}">
    @csrf
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong>Please fix the following errors:</strong>
            <ul class="list-disc list-inside mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="text-sm font-medium block mb-1">Client</label>
            <select name="client_id" class="w-full border rounded px-3 py-2 text-sm" required>
                <option value="">Select client</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm font-medium block mb-1">Invoice Number</label>
            <input type="text" name="invoice_number" class="w-full border rounded px-3 py-2 text-sm" value="{{ isset($nextInvoiceNumber) ? $nextInvoiceNumber : 'RK-00001' }}" required readonly>
        </div>
        <div>
            <label class="text-sm font-medium block mb-1">Start Date</label>
            <input type="date" name="start_date" class="w-full border rounded px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="text-sm font-medium block mb-1">End Date</label>
            <input type="date" name="end_date" class="w-full border rounded px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="text-sm font-medium block mb-1">GST Type</label>
            <select name="gst_type" class="w-full border rounded px-3 py-2 text-sm" required>
                <option value="NOGST">Non-GST</option>
                <option value="GST">GST</option>
                <option value="IGST">IGST</option>
            </select>
        </div>
        <div>
            <label class="text-sm font-medium block mb-1">GSTIN</label>
            <input type="text" name="gstin" class="w-full border rounded px-3 py-2 text-sm">
        </div>
        <div>
            <label class="text-sm font-medium block mb-1">Base Amount</label>
            <input type="number" name="base_amount" id="base-amount-input" class="w-full border rounded px-3 py-2 text-sm" step="0.01" required>
        </div>
        <div class="md:col-span-2">
            <label class="text-sm font-medium block mb-1">GST Calculation</label>
            <div class="space-y-1" id="gst-summary-section">
                <div class="flex justify-between">
                    <span>Base Amount:</span><span id="base-amount">₹0.00</span>
                </div>
                <div class="flex justify-between" id="cgst-row">
                    <span>CGST (9%):</span><span id="cgst-amount">₹0.00</span>
                </div>
                <div class="flex justify-between" id="sgst-row">
                    <span>SGST (9%):</span><span id="sgst-amount">₹0.00</span>
                </div>
                <div class="flex justify-between" id="igst-row">
                    <span>IGST (18%):</span><span id="igst-amount">₹0.00</span>
                </div>
                <div class="flex justify-between">
                    <span>Round Off:</span><span id="roundoff">₹0.00</span>
                </div>
                <div class="flex justify-between font-semibold border-t pt-2 mt-2">
                    <span>Total Amount:</span><span id="total-amount">₹0.00</span>
                </div>
            </div>
        </div>
        <div class="md:col-span-2">
            <label class="text-sm font-medium block mb-1">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2 text-sm" rows="3"></textarea>
        </div>
    
        <input type="hidden" name="subtotal" id="input-subtotal" value="0">
        <input type="hidden" name="gst_amount" id="input-gst-amount" value="0">
        <input type="hidden" name="total" id="input-total" value="0">
    </div>
    <div class="flex justify-end gap-2 mt-6">
        <button type="button" class="px-4 py-2 border rounded-md text-sm" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Save Payment</button>
    </div>
</form>

<script>
function parseNumber(val) {
    return parseFloat(val) || 0;
}

function updateGSTSummary() {
    const amount = parseNumber(document.getElementById('base-amount-input').value);
    const gstType = document.querySelector('select[name="gst_type"]').value;
    let cgst = 0, sgst = 0, igst = 0, gstAmount = 0, total = 0;

    if (gstType === 'GST') {
        cgst = amount * 0.09;
        sgst = amount * 0.09;
        gstAmount = cgst + sgst;
    } else if (gstType === 'IGST') {
        igst = amount * 0.18;
        gstAmount = igst;
    } else {
        cgst = 0;
        sgst = 0;
        igst = 0;
        gstAmount = 0;
    }
    let calculatedTotal = amount + gstAmount;
    let roundedTotal = Math.round(calculatedTotal);
    let roundOff = roundedTotal - calculatedTotal;

    document.getElementById('base-amount').textContent = '\u20b9' + amount.toFixed(2);
    document.getElementById('cgst-amount').textContent = '\u20b9' + cgst.toFixed(2);
    document.getElementById('sgst-amount').textContent = '\u20b9' + sgst.toFixed(2);
    document.getElementById('igst-amount').textContent = '\u20b9' + igst.toFixed(2);
    document.getElementById('roundoff').textContent = '\u20b9' + roundOff.toFixed(2);
    document.getElementById('total-amount').textContent = '\u20b9' + roundedTotal.toFixed(2);

    // Set hidden fields for form submission
    document.getElementById('input-subtotal').value = amount.toFixed(2);
    document.getElementById('input-gst-amount').value = gstAmount.toFixed(2);
    document.getElementById('input-total').value = roundedTotal.toFixed(2);
}

document.getElementById('base-amount-input').addEventListener('input', updateGSTSummary);
document.querySelector('select[name="gst_type"]').addEventListener('change', updateGSTSummary);
document.addEventListener('DOMContentLoaded', function() {
    updateGSTSummary();
});
document.querySelector('select[name="client_id"]').addEventListener('change', function() {
    document.getElementById('debug-client-id').textContent = this.value;
});
</script>
