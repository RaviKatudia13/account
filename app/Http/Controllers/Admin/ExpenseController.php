<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = \App\Models\IncomeExpense::where('type', 'expense')->where('user_id', Auth::id())->with('category')->get();
        $categories = \App\Models\IncExpCategory::where('user_id', Auth::id())->get();
        $employees = \App\Models\Employee::all();
        $vendors = \App\Models\Vendor::all();
        $accounts = \App\Models\Account::where('user_id', Auth::id())->get();
        
        // Initialize expense due paid array
        $expenseDuePaid = [];
        
        // For now, set all paid amounts to 0 since we don't have a dues system for expenses
        foreach ($expenses as $expense) {
            $expenseDuePaid[$expense->id] = 0;
        }
        
        return view('admin.expense.index', compact('expenses', 'categories', 'employees', 'vendors', 'accounts', 'expenseDuePaid'));
    }

    public function create()
    {
        return view('admin.expense.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'inc_exp_category_id' => 'required|exists:inc_exp_categories,id',
            'emp_vendor_type' => 'required|in:employee,vendor',
            'emp_vendor_id' => 'required|integer',
            'description' => 'nullable|string',
        ]);
        $validated['type'] = 'expense';
        $validated['user_id'] = Auth::id();
        \App\Models\IncomeExpense::create($validated);
        return redirect()->route('admin.expense.index')->with('success', 'Expense added successfully.');
    }

    public function edit(\App\Models\IncomeExpense $expense)
    {
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }
        $categories = \App\Models\IncExpCategory::all();
        $employees = \App\Models\Employee::all();
        $vendors = \App\Models\Vendor::all();
        return view('admin.expense.edit', compact('expense', 'categories', 'employees', 'vendors'));
    }

    public function update(Request $request, \App\Models\IncomeExpense $expense)
    {
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'inc_exp_category_id' => 'required|exists:inc_exp_categories,id',
            'emp_vendor_type' => 'required|in:employee,vendor',
            'emp_vendor_id' => 'required|integer',
            'description' => 'nullable|string',
        ]);
        $expense->update($validated);
        return redirect()->route('admin.expense.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(\App\Models\IncomeExpense $expense)
    {
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }
        $expense->delete();
        return redirect()->route('admin.expense.index')->with('success', 'Expense deleted successfully.');
    }

    public function showPaymentModal($id)
    {
        $expense = \App\Models\IncomeExpense::findOrFail($id);
        return view('admin.expense.payment', compact('expense'));
    }

    public function storePayment(Request $request, $id)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_mode' => 'required|string',
            'account' => 'required|string',
            'remarks' => 'nullable|string',
        ]);
        $expense = \App\Models\IncomeExpense::findOrFail($id);
        \App\Models\Payment::create([
            'expense_id' => $expense->id,
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'payment_mode' => $validated['payment_mode'],
            'account' => $validated['account'],
            'remarks' => $validated['remarks'] ?? '',
            'recorded_by' => auth()->user()->name,
        ]);
        return redirect()->route('admin.expense.index')->with('success', 'Payment recorded successfully.');
    }

    public function getRemainingAmount(Request $request)
    {
        $type = $request->input('emp_vendor_type');
        $id = $request->input('emp_vendor_id');
        $date = $request->input('date');
        $expense = \App\Models\IncomeExpense::where('type', 'expense')
            ->where('emp_vendor_type', $type)
            ->where('emp_vendor_id', $id)
            ->where('date', $date)
            ->first();
        $due = \App\Models\ManageDue::where('emp_vendor_type', $type)
            ->where('emp_vendor_id', $id)
            ->where('date', $date)
            ->first();
        $paid = $due ? $due->paid_amount : 0;
        $remaining = $expense ? ($expense->amount - $paid) : 0;
        return response()->json(['remaining' => $remaining]);
    }
} 