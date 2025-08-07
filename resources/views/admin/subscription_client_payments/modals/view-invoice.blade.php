<div class="modal fade" id="viewPaymentModal{{ $payment->id }}" tabindex="-1" aria-labelledby="viewPaymentModalLabel{{ $payment->id }}" aria-hidden="true" style="z-index: 2000;">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewPaymentModalLabel{{ $payment->id }}">Payment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr><th>ID</th><td>{{ $payment->id }}</td></tr>
                    <tr><th>Client</th><td>{{ $payment->client && $payment->client->name ? $payment->client->name : '-' }}</td></tr>
                    <tr><th>Invoice #</th><td>{{ $payment->invoice_number }}</td></tr>
                    <tr><th>GSTIN</th><td>{{ $payment->gstin ?? '-' }}</td></tr>
                    <tr><th>Start Date</th><td>{{ $payment->start_date ? \Carbon\Carbon::parse($payment->start_date)->format('d-M-Y') : '-' }}</td></tr>
                    <tr><th>End Date</th><td>{{ $payment->end_date ? \Carbon\Carbon::parse($payment->end_date)->format('d-M-Y') : '-' }}</td></tr>
                    <tr><th>GST Type</th><td>{{ $payment->gst_type }}</td></tr>
                    <tr><th>Base Amount</th><td>{{ is_numeric($payment->subtotal) ? number_format($payment->subtotal, 2) : '-' }}</td></tr>
                    <tr><th>GST Amount</th><td>{{ is_numeric($payment->gst_amount) ? number_format($payment->gst_amount, 2) : '-' }}</td></tr>
                    <tr><th>Total</th><td>{{ is_numeric($payment->total) ? number_format($payment->total, 2) : '-' }}</td></tr>
                    <tr><th>Description</th><td>{{ $payment->description ?? '-' }}</td></tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
// Fallback: If modal is not showing, force show on click
window.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-bs-target="#viewPaymentModal{{ $payment->id }}"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var modalEl = document.getElementById('viewPaymentModal{{ $payment->id }}');
            if (modalEl) {
                var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }
        });
    });
});
</script> 