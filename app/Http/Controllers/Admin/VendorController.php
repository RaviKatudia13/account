<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorCategory;
use Illuminate\Http\Request;
use App\Models\VendorDue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::with('vendorCategory')->where('user_id', Auth::id())->get();
        $categories = VendorCategory::where('user_id', Auth::id())->get();
        $paymentMethods = \App\Models\PaymentMethod::all();
        $accounts = \App\Models\Account::where('is_active', true)->where('user_id', Auth::id())->get();
        return view('admin.vendor.index', compact('vendors', 'categories', 'paymentMethods', 'accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('vendors')->where(fn($q) => $q->where('user_id', Auth::id())),
            ],
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'vendor_category_id' => 'required|exists:vendor_categories,id',
        ]);
        $validated['user_id'] = Auth::id();
        Vendor::create($validated);
        return redirect()->route('admin.vendors.index')->with('success', 'Vendor created successfully.');
    }

    public function edit(Vendor $vendor)
    {
        if ($vendor->user_id !== Auth::id()) {
            abort(403);
        }
        $categories = VendorCategory::where('user_id', Auth::id())->get();
        return view('admin.vendor.edit', compact('vendor', 'categories'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        if ($vendor->user_id !== Auth::id()) {
            abort(403);
        }
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('vendors')->where(fn($q) => $q->where('user_id', Auth::id()))->ignore($vendor->id),
            ],
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'vendor_category_id' => 'required|exists:vendor_categories,id',
        ]);
        $vendor->update($validated);
        return redirect()->route('admin.vendors.index')->with('success', 'Vendor updated successfully.');
    }

    public function destroy(Vendor $vendor)
    {
        if ($vendor->user_id !== Auth::id()) {
            abort(403);
        }
        $vendor->delete();
        return redirect()->route('admin.vendors.index')->with('success', 'Vendor deleted successfully.');
    }

    public function vendorDueIndex()
    {
        $vendorDues = VendorDue::with('vendor')->where('user_id', Auth::id())->orderByDesc('date')->get();
        $vendors = Vendor::where('user_id', Auth::id())->get();
        $paymentMethods = \App\Models\PaymentMethod::where('user_id', Auth::id())->get();
        $accounts = \App\Models\Account::where('is_active', true)->where('user_id', Auth::id())->get();
        return view('admin.manage_due.vendor_due', compact('vendorDues', 'vendors', 'paymentMethods', 'accounts'));
    }

    public function storeVendorDue(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);
        $remaining = $validated['total_amount'];
        $due = VendorDue::create([
            'vendor_id' => $validated['vendor_id'],
            'date' => $validated['date'],
            'total_amount' => $validated['total_amount'],
            'paid_amount' => 0,
            'remaining_amount' => $remaining,
            'description' => $validated['description'] ?? null,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.manage-due')->with('success', 'Vendor due added successfully!');
    }

    public function payVendorDue(Request $request, $id)
    {
        $due = VendorDue::findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $due->remaining_amount,
            'payment_date' => 'required|date',
            'description' => 'nullable|string',
            'payment_mode' => 'required|string',
            'account_id' => 'required|exists:accounts,id',
        ]);

        \Log::info('payVendorDue validated data', $validated);

        $due->paid_amount += $validated['amount'];
        $due->remaining_amount -= $validated['amount'];
        $due->save();

        // Save to payments table
        $payment = \App\Models\Payment::create([
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'remarks' => $validated['description'] ?? null,
            'vendor_id' => $due->vendor_id,
            'payment_mode' => $validated['payment_mode'],
            'account_id' => $validated['account_id'],
            'type' => 2,
            'recorded_by' => auth()->id(),
        ]);

        \Log::info('payVendorDue created payment', $payment->toArray());

        return redirect()->route('admin.manage-due')->with('success', 'Payment recorded successfully!');
    }

    public function storeVendorPayment(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'description' => 'nullable|string',
            'payment_mode' => 'required|string',
            'account_id' => 'required|exists:accounts,id',
        ]);

        $payment = \App\Models\Payment::create([
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'remarks' => $validated['description'] ?? null,
            'vendor_id' => $vendor->id,
            'payment_mode' => $validated['payment_mode'],
            'account_id' => $validated['account_id'],
            'type' => 2, // 2 = Debit (Vendor Payment)
            'recorded_by' => auth()->id(),
        ]);

        // Apply payment to open dues (oldest first)
        $remaining = $validated['amount'];
        $dues = \App\Models\VendorDue::where('vendor_id', $vendor)
            ->where('remaining_amount', '>', 0)
            ->orderBy('date')
            ->get();

        foreach ($dues as $due) {
            if ($remaining <= 0) break;
            $apply = min($due->remaining_amount, $remaining);
            $due->paid_amount += $apply;
            $due->remaining_amount -= $apply;
            $due->save();
            $remaining -= $apply;
        }

        return redirect()->route('admin.vendors.index')->with('success', 'Payment recorded successfully!');
    }

    public function history($id)
    {
        $vendor = Vendor::findOrFail($id);
        $dues = $vendor->dues()->orderBy('date', 'desc')->get();
        $payments = \App\Models\Payment::where('vendor_id', $id)->orderBy('payment_date', 'desc')->get();
        return view('admin.vendor._history_modal', compact('vendor', 'dues', 'payments'))->render();
    }

    public function storePayment(Request $request, $vendor)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'description' => 'nullable|string',
            'payment_mode' => 'required|string',
            'account_id' => 'required|exists:accounts,id',
        ]);

        \Log::info('storePayment called', [
            'vendor_id' => $vendor,
            'validated' => $validated,
        ]);

        $payment = \App\Models\Payment::create([
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'remarks' => $validated['description'] ?? null,
            'vendor_id' => $vendor,
            'payment_mode' => $validated['payment_mode'],
            'account_id' => $validated['account_id'],
            'type' => 2,
            'recorded_by' => auth()->id(),
        ]);

        \Log::info('Payment created', $payment->toArray());

        // Apply payment to open dues (oldest first)
        $remaining = $validated['amount'];
        $dues = \App\Models\VendorDue::where('vendor_id', $vendor)
            ->where('remaining_amount', '>', 0)
            ->orderBy('date')
            ->get();

        \Log::info('Fetched dues', $dues->toArray());

        foreach ($dues as $due) {
            if ($remaining <= 0) break;
            $apply = min($due->remaining_amount, $remaining);
            $due->paid_amount += $apply;
            $due->remaining_amount -= $apply;
            $due->save();
            \Log::info('Updated due', $due->toArray());
            $remaining -= $apply;
        }

        return redirect()->back()->with('success', 'Payment recorded successfully.');
    }
} 