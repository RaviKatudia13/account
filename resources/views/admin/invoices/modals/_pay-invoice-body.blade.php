{{-- resources/views/invoices/modals/_pay-invoice-body.blade.php --}}
<h2 class="text-lg font-semibold mb-1">Record Payment</h2>
<p class="text-sm text-gray-500 mb-4">Record payment for invoice #INV-2025-001 – Acme Technologies</p>

<form class="space-y-4 text-sm">
    <div>
        <label class="block text-sm font-medium mb-1">Amount (₹)</label>
        <input type="number" placeholder="Enter amount" class="w-full border rounded px-3 py-2 text-sm" />
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Payment Date</label>
        <input type="date" value="{{ now()->format('Y-m-d') }}" class="w-full border rounded px-3 py-2 text-sm" />
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Payment Mode</label>
        <select class="w-full border rounded px-3 py-2 text-sm">
            <option>Select payment mode</option>
            <option>Bank Transfer</option>
            <option>Cash</option>
            <option>Online</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Account</label>
        <select class="w-full border rounded px-3 py-2 text-sm">
            <option>Select account</option>
            <option>SBI Current A/C</option>
            <option>Cash Account</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Remarks</label>
        <textarea rows="3" placeholder="Enter additional details (optional)" class="w-full border rounded px-3 py-2 text-sm"></textarea>
    </div>

    <div class="flex justify-end gap-2">
        <button type="button" @click="paymentModal = false" class="px-4 py-2 border rounded-md text-sm hover:bg-gray-100">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">Record Payment</button>
    </div>
</form>
