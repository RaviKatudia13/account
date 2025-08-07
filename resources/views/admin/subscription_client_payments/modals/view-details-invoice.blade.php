<div class="modal fade" id="viewPaymentModal{{ $payment->id }}" tabindex="-1" aria-labelledby="viewPaymentModalLabel{{ $payment->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewPaymentModalLabel{{ $payment->id }}">Client Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <p><strong>Name:</strong> {{ $payment->client && $payment->client->name ? $payment->client->name : '-' }}</p>
                        <p><strong>Company:</strong> {{ $payment->client && $payment->client->company_name ? $payment->client->company_name : '-' }}</p>
                        <p><strong>Mobile:</strong> {{ $payment->client && $payment->client->mobile ? $payment->client->mobile : '-' }}</p>
                        <p><strong>Email:</strong> {{ $payment->client && $payment->client->email ? $payment->client->email : '-' }}</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <p><strong>Address:</strong> {{ $payment->client && $payment->client->address ? $payment->client->address : '-' }}</p>
                        <p><strong>GST Registered:</strong> {{ $payment->client && $payment->client->gst_registered ? 'Yes' : 'No' }}</p>
                        <p><strong>GSTIN:</strong> {{ $payment->client && $payment->client->gstin ? $payment->client->gstin : '-' }}</p>
                        <p><strong>Payment Mode:</strong> {{ $payment->client && $payment->client->payment_mode ? $payment->client->payment_mode : '-' }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <h6>Payment History</h6>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Payment Mode</th>
                                <th>Account</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\Payment::where('subscription_client_payment_id', $payment->id)->orderBy('payment_date')->get() as $history)
                                <tr>
                                    <td>{{ $history->payment_date }}</td>
                                    <td>{{ number_format($history->amount, 2) }}</td>
                                    <td>{{ $history->paymentMethod->name ?? '-' }}</td>
                                    <td>{{ $history->account->display_name ?? '-' }}</td>
                                    <td>{{ $history->remarks }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> 