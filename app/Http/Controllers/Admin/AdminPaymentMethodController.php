<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminPaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::where('user_id', Auth::id())->get();
        return view('admin.payment_methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        $paymentMethods = PaymentMethod::where('user_id', Auth::id())->get();
        return view('admin.payment_methods.create', compact('paymentMethods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                Rule::unique('payment_methods')->where(fn($q) => $q->where('user_id', Auth::id())),
            ],
        ]);
        PaymentMethod::create(['name' => $request->name]);
        return redirect()->route('admin.payment_methods.index')->with('success', 'Payment method added successfully!');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.payment_methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $request->validate([
            'name' => [
                'required',
                Rule::unique('payment_methods')->where(fn($q) => $q->where('user_id', Auth::id()))->ignore($paymentMethod->id),
            ],
        ]);
        $paymentMethod->name = $request->name;
        $paymentMethod->save();
        return redirect()->route('admin.payment_methods.index')->with('success', 'Payment method updated successfully!');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        return redirect()->route('admin.payment_methods.index')->with('success', 'Payment method deleted successfully.');
    }
} 