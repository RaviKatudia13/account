<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Client;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;

class AdminInvoiceController extends Controller
{
    public function index()
    {
        // Update invoice statuses based on actual payments
        // $this->updateInvoiceStatuses();
        
        $invoices = Invoice::with('client')->where('user_id', Auth::id())->orderByDesc('invoice_date')->get();
        
        // Debug information
        \Log::info('Invoices count: ' . $invoices->count());
        foreach($invoices as $invoice) {
            \Log::info('Invoice ID: ' . $invoice->id . ', Number: ' . $invoice->invoice_number . ', Client: ' . ($invoice->client ? $invoice->client->name : 'NULL'));
        }
        
        $clients = Client::where('user_id', Auth::id())->get();
        $payments = Payment::with(['invoice.client', 'vendor', 'employee', 'account'])->where('user_id', Auth::id())->orderByDesc('payment_date')->get();

        // Generate next invoice number
        $today = date('Ymd');
        $lastInvoice = Invoice::where('invoice_number', 'like', "INV-{$today}-%")
            ->orderByDesc('invoice_number')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int)substr($lastInvoice->invoice_number, -3);
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }
        $nextInvoiceNumber = "INV-{$today}-{$nextNumber}";

        $paymentMethods = PaymentMethod::where('user_id', Auth::id())->get();
        $accounts = Account::where('is_active', true)->where('user_id', Auth::id())->get();

        return view('admin.invoices.index', compact('invoices', 'clients', 'payments', 'nextInvoiceNumber', 'paymentMethods', 'accounts'));
    }

    private function updateInvoiceStatuses()
    {
        try {
            $invoices = Invoice::all();
            \Log::info('Updating statuses for ' . $invoices->count() . ' invoices');
            
            foreach ($invoices as $invoice) {
                $totalPaid = $invoice->payments()->sum('amount');
                \Log::info('Invoice ' . $invoice->id . ' - Total: ' . $invoice->total . ', Paid: ' . $totalPaid);
                
                if ($totalPaid == 0) {
                    $invoice->status = 'Due';
                } elseif (abs($totalPaid - $invoice->total) < 0.01) {
                    $invoice->status = 'Paid';
                } else {
                    $invoice->status = 'Partial';
                }
                
                $invoice->save();
                \Log::info('Invoice ' . $invoice->id . ' status updated to: ' . $invoice->status);
            }
        } catch (\Exception $e) {
            \Log::error('Error updating invoice statuses: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        // Debug: Log the incoming request data
        \Log::info('Invoice creation request data:', $request->all());
        
        try {
            $data = $request->validate([
                'client_id' => 'required|exists:clients,id',
                'invoice_number' => 'required|unique:invoices,invoice_number',
                'invoice_date' => 'required|date',
                'items' => 'required|array',
                'items.*.description' => 'required|string',
                'items.*.rate' => 'required|numeric',
                'items.*.gst_type' => 'required|in:Non-GST,GST,IGST',
                'items.*.gst_percent' => 'required|numeric',
                'items.*.amount' => 'required|numeric',
                'subtotal' => 'required|numeric',
                'gst_type' => 'required|in:Non-GST,GST,IGST',
                'gst_amount' => 'required|numeric',
                'total' => 'required|numeric',
                'notes' => 'nullable|string',
            ]);
            
            \Log::info('Validation passed, creating invoice with data:', $data);
            
            $data['status'] = 'Due';
            $data['user_id'] = Auth::id();
            $data['items'] = json_encode($data['items']);
            Invoice::create($data);
            
            \Log::info('Invoice created successfully');
            return redirect()->route('admin.invoices.index')->with('success', 'Invoice created successfully.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed:', $e->errors());
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error creating invoice: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create invoice: ' . $e->getMessage()])->withInput();
        }
    }

    public function pay(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_mode' => 'required|string',
            'account_id' => 'required|exists:accounts,id',
            'remarks' => 'nullable|string',
        ]);

        // Calculate total payments made so far
        $totalPaid = $invoice->payments()->sum('amount');
        $remainingAmount = $invoice->total - $totalPaid;

        // Validate payment amount doesn't exceed remaining amount
        if ($data['amount'] > $remainingAmount) {
            return back()->withErrors(['amount' => 'Payment amount cannot exceed remaining balance of ₹' . number_format($remainingAmount, 2)]);
        }

        // Get account information
        $account = Account::find($data['account_id']);

        $data['invoice_id'] = $invoice->id;
        $data['user_id'] = Auth::id();
        $data['recorded_by'] = auth()->id();
        $data['type'] = 1;
        $data['payment_mode'] = $request->input('payment_mode');
        Payment::create($data);

        // Calculate new total paidq
        $newTotalPaid = $totalPaid + $data['amount'];
        
        // Update invoice status based on payment
        if (abs($newTotalPaid - $invoice->total) < 0.01) {
            $invoice->status = 'Paid';
        } else {
            $invoice->status = 'Partial';
        }
        
        $invoice->save();

        $message = abs($newTotalPaid - $invoice->total) < 0.01
            ? 'Payment recorded and invoice marked as paid.'
            : 'Partial payment recorded. Remaining balance: ₹' . number_format($invoice->total - $newTotalPaid, 2);

        return redirect()->route('admin.invoices.index')->with('success', $message);
    }

    public function payments()
    {
        $payments = Payment::with(['invoice.client', 'vendor', 'employee', 'account'])->where('user_id', Auth::id())->orderByDesc('payment_date')->get();
        $paymentMethods = PaymentMethod::where('user_id', Auth::id())->get();
        return view('admin.payments', compact('payments', 'paymentMethods'));
    }
} 