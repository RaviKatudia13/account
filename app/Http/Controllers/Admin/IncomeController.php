<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    public function index()
    {
        $incomes = \App\Models\IncomeExpense::where('type', 'income')->where('user_id', Auth::id())->with('category')->get();
        $categories = \App\Models\IncExpCategory::where('user_id', Auth::id())->get();
        $employees = \App\Models\Employee::all();
        $vendors = \App\Models\Vendor::all();
        $accounts = \App\Models\Account::where('user_id', Auth::id())->get();
        return view('admin.income.index', compact('incomes', 'categories', 'employees', 'vendors', 'accounts'));
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
        $validated['type'] = 'income';
        $validated['user_id'] = Auth::id();
        \App\Models\IncomeExpense::create($validated);
        return redirect()->route('admin.income.index')->with('success', 'Income added successfully.');
    }

    public function edit(\App\Models\IncomeExpense $income)
    {
        if ($income->user_id !== Auth::id()) {
            abort(403);
        }
        $categories = \App\Models\IncExpCategory::all();
        $employees = \App\Models\Employee::all();
        $vendors = \App\Models\Vendor::all();
        return view('admin.income.edit', compact('income', 'categories', 'employees', 'vendors'));
    }

    public function update(Request $request, \App\Models\IncomeExpense $income)
    {
        if ($income->user_id !== Auth::id()) {
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
        $income->update($validated);
        return redirect()->route('admin.income.index')->with('success', 'Income updated successfully.');
    }

    public function destroy(\App\Models\IncomeExpense $income)
    {
        if ($income->user_id !== Auth::id()) {
            abort(403);
        }
        $income->delete();
        return redirect()->route('admin.income.index')->with('success', 'Income deleted successfully.');
    }
} 