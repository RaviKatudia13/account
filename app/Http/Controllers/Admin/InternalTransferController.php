<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternalTransfer;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InternalTransferController extends Controller
{
    public function index()
    {
        $transfers = InternalTransfer::where('user_id', Auth::id())->orderByDesc('created_at')->get();
        $paymentMethods = PaymentMethod::all();
        $accounts = Account::where('user_id', Auth::id())->get();
        return view('admin.internal_transfer.list', compact('transfers', 'paymentMethods', 'accounts'));
    }

    public function create()
    {
        return view('admin.internal_transfer.add');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'debit_account' => 'required|string|max:255',
            'credit_account' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
        ]);
        $validated['user_id'] = Auth::id();
        
        // Create the internal transfer
        $transfer = InternalTransfer::create($validated);
        
        // Find account IDs by name
        $debitAccount = Account::where('name', $validated['debit_account'])->first();
        $creditAccount = Account::where('name', $validated['credit_account'])->first();
        
        // Get default payment method ID (first available)
        $defaultPaymentMethodId = PaymentMethod::first()->id ?? 1;
        
        // Create payment records for both debit and credit accounts
        Payment::create([
            'amount' => $validated['amount'],
            'payment_date' => now()->format('Y-m-d'),
            'payment_mode' => $debitAccount && $debitAccount->payment_mode_id ? $debitAccount->payment_mode_id : $defaultPaymentMethodId,
            'account_id' => $debitAccount ? $debitAccount->id : null,
            'account' => $validated['debit_account'],
            'remarks' => 'Internal Transfer - Debit: ' . ($validated['description'] ?? ''),
            'recorded_by' => auth()->id(),
            'type' => 2, // Debit
            'internal_transfer' => 1,
        ]);
        
        Payment::create([
            'amount' => $validated['amount'],
            'payment_date' => now()->format('Y-m-d'),
            'payment_mode' => $creditAccount && $creditAccount->payment_mode_id ? $creditAccount->payment_mode_id : $defaultPaymentMethodId,
            'account_id' => $creditAccount ? $creditAccount->id : null,
            'account' => $validated['credit_account'],
            'remarks' => 'Internal Transfer - Credit: ' . ($validated['description'] ?? ''),
            'recorded_by' => auth()->id(),
            'type' => 1, // Credit
            'internal_transfer' => 1,
        ]);
        
        return redirect()->route('admin.internal-transfer.list')->with('success', 'Internal transfer added successfully!');
    }
} 