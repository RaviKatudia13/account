<div class="modal fade" id="editPaymentModal{{ $payment->id }}" tabindex="-1" aria-labelledby="editPaymentModalLabel{{ $payment->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPaymentModalLabel{{ $payment->id }}">Edit Subscription Client Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.subscription-client-payments.update', $payment->id) }}">
                @csrf
                @method('PUT')
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
                        <select name="subscription_client_id" class="w-full border rounded px-3 py-2 text-sm" required>
                            <option value="">Select a client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ $payment->subscription_client_id == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium block mb-1">Invoice Date</label>
                        <input type="date" name="invoice_date" class="w-full border rounded px-3 py-2 text-sm" value="{{ $payment->invoice_date }}" required>
                    </div>
                    <div>
                        <label class="text-sm font-medium block mb-1">Invoice Number</label>
                        <input type="text" name="invoice_number" class="w-full border rounded px-3 py-2 text-sm" value="{{ $payment->invoice_number }}" required readonly>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="text-sm font-medium block mb-1">Status</label>
                        <select name="status" class="w-full border rounded px-3 py-2 text-sm" required>
                            <option value="Paid" {{ $payment->status == 'Paid' ? 'selected' : '' }}>Paid</option>
                            <option value="Due" {{ $payment->status == 'Due' ? 'selected' : '' }}>Due</option>
                            <option value="Partial" {{ $payment->status == 'Partial' ? 'selected' : '' }}>Partial</option>
                        </select>
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-semibold mb-2">Service Items</h3>
                    <div class="grid grid-cols-4 gap-2 items-center text-xs font-semibold text-gray-600 mb-1">
                        <div class="col-span-2">Description</div>
                        <div>Amount (₹)</div>
                        <div>GST/IGST</div>
                    </div>
                    <div id="edit-items-list-{{ $payment->id }}">
                        @foreach($payment->items as $idx => $item)
                        <div class="grid grid-cols-4 gap-2 items-center mb-3">
                            <input type="text" name="items[{{ $idx }}][description]" class="col-span-2 border rounded px-2 py-1 text-sm" value="{{ $item['description'] }}" required>
                            <input type="number" name="items[{{ $idx }}][rate]" class="border rounded px-2 py-1 text-sm" value="{{ $item['rate'] }}" min="0" step="0.01" required>
                            <select name="items[{{ $idx }}][gst_type]" class="border rounded px-2 py-1 text-sm" required>
                                <option value="Non-GST" {{ $item['gst_type'] == 'Non-GST' ? 'selected' : '' }}>Non-GST</option>
                                <option value="GST" {{ $item['gst_type'] == 'GST' ? 'selected' : '' }}>GST 18%</option>
                                <option value="IGST" {{ $item['gst_type'] == 'IGST' ? 'selected' : '' }}>IGST 18%</option>
                            </select>
                            <input type="hidden" name="items[{{ $idx }}][gst_percent]" value="{{ $item['gst_percent'] }}">
                            <input type="hidden" name="items[{{ $idx }}][amount]" value="{{ $item['amount'] }}">
                            <div class="text-sm font-medium">₹{{ number_format($item['amount'], 2) }}</div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="text-blue-600 text-sm font-medium mb-4" onclick="addEditItemRow({{ $payment->id }})">+ Add Item</button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium block mb-1">Notes</label>
                        <textarea name="notes" class="w-full border rounded px-3 py-2 text-sm" rows="3">{{ $payment->notes }}</textarea>
                    </div>
                    <div class="text-sm">
                        <div class="space-y-1" id="edit-summary-section-{{ $payment->id }}">
                            <div class="flex justify-between">
                                <span>Subtotal:</span><span id="edit-subtotal-{{ $payment->id }}">₹{{ number_format($payment->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between" id="edit-tax-breakdown-{{ $payment->id }}">
                                <span>CGST (9%):</span><span id="edit-cgst-{{ $payment->id }}">₹{{ number_format($payment->gst_amount/2, 2) }}</span>
                            </div>
                            <div class="flex justify-between" id="edit-tax-breakdown2-{{ $payment->id }}">
                                <span>SGST (9%):</span><span id="edit-sgst-{{ $payment->id }}">₹{{ number_format($payment->gst_amount/2, 2) }}</span>
                            </div>
                            <div class="flex justify-between" id="edit-igst-row-{{ $payment->id }}">
                                <span>IGST (18%):</span><span id="edit-igst-{{ $payment->id }}">₹0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Round Off:</span><span id="edit-roundoff-{{ $payment->id }}">₹0.00</span>
                            </div>
                            <div class="flex justify-between font-semibold border-t pt-2 mt-2">
                                <span>Total Amount:</span><span id="edit-total-{{ $payment->id }}">₹{{ number_format($payment->total, 2) }}</span>
                            </div>
                        </div>
                        <input type="hidden" name="subtotal" value="{{ $payment->subtotal }}">
                        <input type="hidden" name="gst_type" value="{{ $payment->gst_type }}">
                        <input type="hidden" name="gst_amount" value="{{ $payment->gst_amount }}">
                        <input type="hidden" name="total" value="{{ $payment->total }}">
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" class="px-4 py-2 border rounded-md text-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function addEditItemRow(paymentId) {
    const itemsList = document.getElementById('edit-items-list-' + paymentId);
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
</script> 