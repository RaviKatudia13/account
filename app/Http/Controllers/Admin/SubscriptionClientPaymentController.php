<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionClientPayment;
use App\Models\SubscriptionClientList;
use App\Models\Category;
use App\Models\Account;
use App\Models\PaymentMethod;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class SubscriptionClientPaymentController extends Controller
{
    public function index()
    {
        $payments = SubscriptionClientPayment::with('client')->where('user_id', Auth::id())->paginate(15);
        $clients = SubscriptionClientList::where('user_id', Auth::id())->get();
        $categories = Category::where('user_id', Auth::id())->get();
        $accounts = Account::where('is_active', true)->where('user_id', Auth::id())->get();
        $paymentMethods = PaymentMethod::all();
        $maxTotals = SubscriptionClientPayment::selectRaw('client_id, MAX(total) as max_total')
            ->where('user_id', Auth::id())
            ->groupBy('client_id')
            ->pluck('max_total', 'client_id');

        // Auto-increment invoice number
        $last = SubscriptionClientPayment::orderByDesc('id')->first();
        if ($last && preg_match('/RK-(\d+)/', $last->invoice_number, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
            $nextInvoiceNumber = 'RK-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        } else {
            $nextInvoiceNumber = 'RK-00001';
        }

        return view('admin.subscription_client_payments.index', compact('payments', 'clients', 'categories', 'nextInvoiceNumber', 'accounts', 'paymentMethods', 'maxTotals'));
    }

    public function create()
    {
        $clients = SubscriptionClientList::all();
        return view('admin.subscription_client_payments.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:subscriptions_client_list,id',
            'gst_type' => 'required|in:GST,IGST,NOGST',
            'invoice_number' => 'required|string|max:255|unique:subscription_client_payments,invoice_number',
            'gstin' => 'nullable|string|max:15',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'subtotal' => 'required|numeric',
            'gst_amount' => 'required|numeric',
            'total' => 'required|numeric',
            'description' => 'nullable|string',
        ]);
        $validated['user_id'] = Auth::id();
        SubscriptionClientPayment::create($validated);
        return redirect()->route('admin.subscription-client-payments.index')->with('success', 'Payment added successfully.');
    }

    public function edit($id)
    {
        $payment = SubscriptionClientPayment::findOrFail($id);
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }
        return view('admin.subscription_client_payments.edit', compact('payment'));
    }

    public function update(Request $request, $id)
    {
        $payment = SubscriptionClientPayment::findOrFail($id);
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }
        $validated = $request->validate([
            'client_id' => 'required|exists:subscriptions_client_list,id',
            'gst_type' => 'required|in:GST,IGST,NOGST',
            'invoice_number' => 'required|string|max:255|unique:subscription_client_payments,invoice_number,' . $payment->id,
            'gstin' => 'nullable|string|max:15',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'subtotal' => 'required|numeric',
            'gst_amount' => 'required|numeric',
            'total' => 'required|numeric',
            'description' => 'nullable|string',
        ]);
        $payment->update($validated);
        return redirect()->route('admin.subscription-client-payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy($id)
    {
        $payment = SubscriptionClientPayment::findOrFail($id);
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }
        $payment->delete();
        return redirect()->route('admin.subscription-client-payments.index')->with('success', 'Payment deleted successfully.');
    }

    public function recordPayment(Request $request, $paymentId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_mode_id' => 'required|exists:payment_methods,id',
            'account_id' => 'required|exists:accounts,id',
            'payment_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        $subscriptionPayment = SubscriptionClientPayment::findOrFail($paymentId);

        Payment::create([
            'subscription_client_payment_id' => $subscriptionPayment->id,
            'client_id' => $subscriptionPayment->client_id,
            'amount' => $request->amount,
            'payment_mode' => $request->payment_mode_id,
            'account_id' => $request->account_id,
            'payment_date' => $request->payment_date,
            'remarks' => $request->remarks,
            'type' => 1,
            'recorded_by' => auth()->id(),
        ]);

        // Update paid_amount and status
        $subscriptionPayment->paid_amount = ($subscriptionPayment->paid_amount ?? 0) + $request->amount;
        if ($subscriptionPayment->paid_amount >= $subscriptionPayment->total) {
            $subscriptionPayment->status = 'Paid';
        } elseif ($subscriptionPayment->paid_amount > 0) {
            $subscriptionPayment->status = 'Partial';
        } else {
            $subscriptionPayment->status = 'Due';
        }
        $subscriptionPayment->save();

        return redirect()->back()->with('success', 'Payment recorded successfully.');
    }
} 