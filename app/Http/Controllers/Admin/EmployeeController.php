<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeCategory;
use Illuminate\Http\Request;
use App\Models\EmployeeDue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('employeeCategory')->where('user_id', Auth::id())->get();
        $categories = EmployeeCategory::where('user_id', Auth::id())->get();
        $paymentMethods = \App\Models\PaymentMethod::all();
        $accounts = \App\Models\Account::where('is_active', true)->where('user_id', Auth::id())->get();
        return view('admin.employee.index', compact('employees', 'categories', 'paymentMethods', 'accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('employees')->where(fn($q) => $q->where('user_id', Auth::id())),
            ],
            'number' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'join_date' => 'required|date',
            'employee_category_id' => 'required|exists:employee_categories,id',
        ]);
        $validated['user_id'] = Auth::id();
        Employee::create($validated);
        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully.');
    }

    public function edit(Employee $employee)
    {
        if ($employee->user_id !== Auth::id()) {
            abort(403);
        }
        $categories = EmployeeCategory::where('user_id', Auth::id())->get();
        return view('admin.employee.edit', compact('employee', 'categories'));
    }

    public function update(Request $request, Employee $employee)
    {
        if ($employee->user_id !== Auth::id()) {
            abort(403);
        }
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('employees')->where(fn($q) => $q->where('user_id', Auth::id()))->ignore($employee->id),
            ],
            'number' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'join_date' => 'required|date',
            'employee_category_id' => 'required|exists:employee_categories,id',
        ]);
        $employee->update($validated);
        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->user_id !== Auth::id()) {
            abort(403);
        }
        $employee->delete();
        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully.');
    }

    public function storeEmployeeDue(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);
        $remaining = $validated['total_amount'];
        $due = EmployeeDue::create([
            'employee_id' => $validated['employee_id'],
            'date' => $validated['date'],
            'total_amount' => $validated['total_amount'],
            'paid_amount' => 0,
            'remaining_amount' => $remaining,
            'description' => $validated['description'] ?? null,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.employee-due')->with('success', 'Employee due added successfully!');
    }

    public function employeeDueIndex()
    {
        $employeeDues = EmployeeDue::with('employee')->where('user_id', Auth::id())->orderByDesc('date')->get();
        $employees = Employee::where('user_id', Auth::id())->get();
        $paymentMethods = \App\Models\PaymentMethod::where('user_id', Auth::id())->get();
        $accounts = \App\Models\Account::where('is_active', true)->where('user_id', Auth::id())->get();
        return view('admin.manage_due.employee_due', compact('employees', 'employeeDues', 'paymentMethods', 'accounts'));
    }

    public function payEmployeeDue(Request $request, $id)
    {
        $due = EmployeeDue::findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $due->remaining_amount,
            'payment_date' => 'required|date',
            'description' => 'nullable|string',
            'payment_mode' => 'required|string',
            'account_id' => 'required|exists:accounts,id',
        ]);

        $due->paid_amount += $validated['amount'];
        $due->remaining_amount -= $validated['amount'];
        $due->save();

        // Get account information
        $account = \App\Models\Account::find($validated['account_id']);

        // Save to payments table
        \App\Models\Payment::create([
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'remarks' => $validated['description'] ?? null,
            'employee_due_id' => $due->id,
            'payment_mode' => $validated['payment_mode'],
            'account_id' => $validated['account_id'],
            'type' => 2,
            'recorded_by' => auth()->id(),
        ]);

        return redirect()->route('admin.employee-due')->with('success', 'Payment recorded successfully!');
    }

    public function storeEmployeePayment(Request $request, Employee $employee)
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
            'employee_id' => $employee->id,
            'payment_mode' => $validated['payment_mode'],
            'account_id' => $validated['account_id'],
            'type' => 2,
            'recorded_by' => auth()->id(),
        ]);

        // Apply payment to open dues (oldest first)
        $remaining = $validated['amount'];
        $dues = $employee->dues()->where('remaining_amount', '>', 0)->orderBy('date')->get();
        foreach ($dues as $due) {
            if ($remaining <= 0) break;
            $apply = min($due->remaining_amount, $remaining);
            $due->paid_amount += $apply;
            $due->remaining_amount -= $apply;
            $due->save();
            $remaining -= $apply;
        }

        return redirect()->route('admin.employees.index')->with('success', 'Payment recorded successfully!');
    }

    public function history($id)
    {
        $employee = Employee::findOrFail($id);
        $dues = $employee->dues()->orderBy('date', 'desc')->get();
        $payments = \App\Models\Payment::where('employee_id', $id)->orderBy('payment_date', 'desc')->get();
        return view('admin.employee._history_modal', compact('employee', 'dues', 'payments'))->render();
    }

    public function storePayment(Request $request, $employee)
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
            'employee_id' => $employee,
            'payment_mode' => $validated['payment_mode'],
            'account_id' => $validated['account_id'],
            'type' => 2,
            'recorded_by' => auth()->id(),
        ]);

        // Apply payment to open dues (oldest first)
        $remaining = $validated['amount'];
        $dues = \App\Models\EmployeeDue::where('employee_id', $employee)
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

        return redirect()->back()->with('success', 'Payment recorded successfully.');
    }
} 