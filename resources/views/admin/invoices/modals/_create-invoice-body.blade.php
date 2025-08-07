<h2 class="text-lg font-semibold mb-1">Create New Invoice</h2>
<p class="text-sm text-gray-500 mb-4">Create a new invoice for your client. The GST/IGST breakdown will be calculated automatically.</p>

<form class="space-y-4" method="POST" action="{{ route('admin.invoices.store') }}">
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
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="text-sm font-medium block mb-1">Client</label>
            <select name="client_id" class="w-full border rounded px-3 py-2 text-sm" required>
                <option value="">Select a client</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name ?? $client->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm font-medium block mb-1">Invoice Date</label>
            <input type="date" name="invoice_date" class="w-full border rounded px-3 py-2 text-sm" value="{{ date('Y-m-d') }}" required>
        </div>
        <div>
            <label class="text-sm font-medium block mb-1">Invoice Number</label>
            <input type="text" name="invoice_number" class="w-full border rounded px-3 py-2 text-sm" value="{{ isset($nextInvoiceNumber) ? $nextInvoiceNumber : 'INV-'.date('Ymd').'-001' }}" required readonly>
        </div>
    </div>

    <div>
        <h3 class="text-sm font-semibold mb-2">Service Items</h3>
        <div class="grid grid-cols-4 gap-2 items-center text-xs font-   semibold text-gray-600 mb-1">
            <div class="col-span-2">Description</div>
            <div>Amount (₹)</div>
            <div>GST/IGST</div>
        </div>
        <div id="items-list">
            <div class="grid grid-cols-4 gap-2 items-center mb-3">
                <input type="text" name="items[0][description]" class="col-span-2 border rounded px-2 py-1 text-sm" placeholder="Enter description" required>
                <input type="number" name="items[0][rate]" class="border rounded px-2 py-1 text-sm" value="0" min="0" step="0.01" required>
                <select name="items[0][gst_type]" class="border rounded px-2 py-1 text-sm" required>
                    <option value="Non-GST">Non-GST</option>
                    <option value="GST">GST 18%</option>
                    <option value="IGST">IGST 18%</option>
                </select>
                <input type="hidden" name="items[0][gst_percent]" value="18">
                <input type="hidden" name="items[0][amount]" value="0">
                <div class="text-sm font-medium" hidden>₹0.00</div>
            </div>
        </div>
        <button type="button" class="text-blue-600 text-sm font-medium mb-4" onclick="addItemRow()">+ Add Item</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="text-sm font-medium block mb-1">Notes</label>
            <textarea name="notes" class="w-full border rounded px-3 py-2 text-sm" rows="3">Payment Terms: Net 30 days from the date of invoice.</textarea>
        </div>
        <div class="text-sm">
            <div class="space-y-1" id="summary-section">
                <div class="flex justify-between">
                    <span>Subtotal:</span><span id="subtotal">₹0.00</span>
                </div>
                <div class="flex justify-between" id="tax-breakdown">
                    <span>CGST (9%):</span><span id="cgst">₹0.00</span>
                </div>
                <div class="flex justify-between" id="tax-breakdown2">
                    <span>SGST (9%):</span><span id="sgst">₹0.00</span>
                </div>
                <div class="flex justify-between" id="igst-row">
                    <span>IGST (18%):</span><span id="igst">₹0.00</span>
                </div>
                <div class="flex justify-between">
                    <span>Round Off:</span><span id="roundoff">₹0.00</span>
                </div>
                <div class="flex justify-between font-semibold border-t pt-2 mt-2">
                    <span>Total Amount:</span><span id="total">₹0.00</span>
                </div>
            </div>
            <input type="hidden" name="subtotal" id="input-subtotal">
            <input type="hidden" name="gst_type" id="input-gst-type">
            <input type="hidden" name="gst_amount" id="input-gst-amount">
            <input type="hidden" name="total" id="input-total">
            <div class="mt-4 text-xs text-gray-500">
                <p class="font-semibold">Bank Details:</p>
                <p>Acc Name: IT Admin Services</p>
                <p>Acc No: 1234567890</p>
                <p>Bank: State Bank of India</p>
                <p>IFSC: SBIN0001234</p>
            </div>
        </div>
    </div>

    <div class="flex justify-end gap-2 mt-6">
        <button type="button" class="px-4 py-2 border rounded-md text-sm">Cancel</button>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Save Invoice</button>
    </div>
</form>
<script>
function parseNumber(val) {
    return parseFloat(val) || 0;
}

function addItemRow() {
    const itemsList = document.getElementById('items-list');
    const idx = itemsList.querySelectorAll('.grid').length;
    const row = document.createElement('div');
    row.className = 'grid grid-cols-4 gap-2 items-center mb-3';
    row.innerHTML = `
        <input type="text" name="items[${idx}][description]" class="col-span-2 border rounded px-2 py-1 text-sm" placeholder="Enter description" required>
        <input type="number" name="items[${idx}][rate]" class="border rounded px-2 py-1 text-sm" value="0" min="0" step="0.01" required>
        <select name="items[${idx}][gst_type]" class="border rounded px-2 py-1 text-sm" required>
            <option value="Non-GST">Non-GST</option>
            <option value="GST">GST 18%</option>
            <option value="IGST">IGST 18%</option>
        </select>
        <input type="hidden" name="items[${idx}][gst_percent]" value="18">
        <input type="hidden" name="items[${idx}][amount]" value="0">
        <div class="text-sm font-medium">₹0.00</div>
    `;
    itemsList.appendChild(row);
}

function updateInvoiceSummary() {
    let items = document.querySelectorAll('#items-list > .grid');
    let subtotal = 0, gstAmount = 0, total = 0;
    let gstTotal = 0, igstTotal = 0;

    items.forEach((row, idx) => {
        let rate = parseNumber(row.querySelector('input[name^="items["][name$="[rate]"]').value);
        let gstTypeField = row.querySelector('select[name^="items["][name$="[gst_type]"]');
        let gstPercent = parseNumber(row.querySelector('input[name^="items["][name$="[gst_percent]"]').value);
        let amountField = row.querySelector('input[name^="items["][name$="[amount]"]');
        let amount = rate;
        let gst = 0;

        if (gstTypeField.value === 'GST') {
            gst = rate * gstPercent / 100;
            gstTotal += gst;
        } else if (gstTypeField.value === 'IGST') {
            gst = rate * gstPercent / 100;
            igstTotal += gst;
        }
        // Non-GST items have no tax
        amount += gst;
        subtotal += rate;
        gstAmount += gst;
        total += amount;

        // Update the visible amount (if you have a span/div for it)
        let amountDiv = row.querySelector('div.text-sm.font-medium');
        if (amountDiv) amountDiv.textContent = '₹' + amount.toFixed(2);

        // Update the hidden amount field
        if (amountField) amountField.value = amount.toFixed(2);
    });

    // Update summary fields
    document.getElementById('subtotal').textContent = '₹' + subtotal.toFixed(2);
    document.getElementById('total').textContent = '₹' + total.toFixed(2);
    document.getElementById('input-subtotal').value = subtotal.toFixed(2);
    document.getElementById('input-gst-type').value = (gstTotal > 0 || igstTotal > 0) ? (igstTotal > 0 ? 'IGST' : 'GST') : 'Non-GST';
    document.getElementById('input-gst-amount').value = gstAmount.toFixed(2);
    document.getElementById('input-total').value = total.toFixed(2);

    // Calculate round off
    let calculatedTotal = subtotal + gstAmount;
    let roundedTotal = Math.round(calculatedTotal);
    let roundOff = roundedTotal - calculatedTotal;
    
    // Update round off display
    document.getElementById('roundoff').textContent = '₹' + roundOff.toFixed(2);
    
    // Update total to rounded amount
    document.getElementById('total').textContent = '₹' + roundedTotal.toFixed(2);
    document.getElementById('input-total').value = roundedTotal.toFixed(2);

    // Always show all tax options with calculated amounts
    document.getElementById('cgst').textContent = '₹' + (gstTotal/2).toFixed(2);
    document.getElementById('sgst').textContent = '₹' + (gstTotal/2).toFixed(2);
    document.getElementById('igst').textContent = '₹' + igstTotal.toFixed(2);
    
    // Always display all tax rows
    document.getElementById('tax-breakdown').style.display = 'flex';
    document.getElementById('tax-breakdown2').style.display = 'flex';
    document.getElementById('igst-row').style.display = 'flex';
}

// Call updateInvoiceSummary on input changes
// (use event delegation for dynamic rows)
document.addEventListener('input', function(e) {
    if (e.target.closest('#items-list')) {
        updateInvoiceSummary();
    }
});

// Handle select changes for GST type
document.addEventListener('change', function(e) {
    if (e.target.closest('#items-list')) {
        updateInvoiceSummary();
    }
});

// Also update before submit
document.querySelector('form[action="{{ route('admin.invoices.store') }}"]').addEventListener('submit', function(e) {
    console.log('Form submission started');
    updateInvoiceSummary();
    
    // Log form data
    const formData = new FormData(this);
    console.log('Form data:');
    for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
    }
    
    // Check if required fields are filled
    const clientId = this.querySelector('select[name="client_id"]').value;
    const description = this.querySelector('input[name="items[0][description]"]').value;
    const rate = this.querySelector('input[name="items[0][rate]"]').value;
    
    console.log('Client ID:', clientId);
    console.log('Description:', description);
    console.log('Rate:', rate);
    
    if (!clientId || !description || !rate || rate == 0) {
        e.preventDefault();
        alert('Please fill in all required fields: Client, Description, and Rate');
        return false;
    }
    
});

// Initialize the summary when the modal opens
document.addEventListener('DOMContentLoaded', function() {
    updateInvoiceSummary();
});

// Also initialize when the modal is shown (for Bootstrap modals)
document.addEventListener('shown.bs.modal', function() {
    updateInvoiceSummary();
});
</script>
