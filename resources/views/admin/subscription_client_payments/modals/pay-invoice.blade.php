{{-- resources/views/invoices/modals/pay-invoice.blade.php --}}

<div class="modal fade" id="payPaymentModal{{ $payment->id }}" tabindex="-1" aria-labelledby="payPaymentModalLabel{{ $payment->id }}" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="payPaymentModalLabel{{ $payment->id }}">Record Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @include('admin.subscription_client_payments.modals._pay-invoice-body')
            </div>
        </div>
    </div>
</div>