<!-- Bootstrap Modal for Pay Invoice -->
<div class="modal fade" id="payInvoiceModal" tabindex="-1" aria-labelledby="payInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="payInvoiceModalLabel">Pay Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @include('admin.invoices.modals._pay-invoice-body')
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the pay invoice modal
    var payModal = document.getElementById('payInvoiceModal');
    if (payModal && typeof bootstrap !== 'undefined') {
        new bootstrap.Modal(payModal);
    }
    
    // Function to open pay invoice modal
    window.openPayInvoiceModal = function(invoiceId) {
        var modal = document.getElementById('payInvoiceModal');
        if (modal && typeof bootstrap !== 'undefined') {
            // Load invoice data via AJAX if needed
            var bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        }
    };
});
</script>