<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateSubscriptionClientPaymentIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-subscription-client-payment-ids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $updated = 0;
        $payments = \App\Models\Payment::whereNull('subscription_client_payment_id')->get();
        foreach ($payments as $payment) {
            // Try to find a matching SubscriptionClientPayment
            $match = \App\Models\SubscriptionClientPayment::where('client_id', $payment->client_id)
                ->where('total', $payment->amount)
                ->whereDate('created_at', $payment->payment_date)
                ->first();
            if ($match) {
                $payment->subscription_client_payment_id = $match->id;
                $payment->save();
                $updated++;
                $this->info("Updated payment ID {$payment->id} with subscription_client_payment_id {$match->id}");
            }
        }
        $this->info("Done. Updated {$updated} payments.");
        return 0;
    }
}
