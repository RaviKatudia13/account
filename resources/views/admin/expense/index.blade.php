@extends('layouts.admin')

@section('title', 'Expense List')

@section('content')
<div class="container mt-16">
    <h1 class="h3 mb-4 fw-bold">Expense List</h1>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addExpenseModal"><i class="fa fa-plus"></i> Add Expense</button>
    <!-- Add Expense Modal -->
    <div class="modal fade @if($errors->has('emp_vendor_type') || $errors->has('emp_vendor_id')) show d-block @endif" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel" aria-hidden="true" @if($errors->has('emp_vendor_type') || $errors->has('emp_vendor_id')) style="display:block;" @endif>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addExpenseModalLabel">Add Expense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.expense.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <!-- <label class="form-label">Source</label> -->
                                    <input class="form-check-input" type="radio" name="emp_vendor_type" id="addTypeEmployee" value="employee" checked disabled hidden>
                                    <label class="form-check-label" for="addTypeEmployee" hidden>Employee</label>
                            <input type="hidden" name="emp_vendor_type" value="employee">
                        </div>
                        <div class="mb-3" id="addEmployeeDropdown">
                            <label for="add_emp_vendor_id_employee" class="form-label">Employee</label>
                            <select name="emp_vendor_id" id="add_emp_vendor_id_employee" class="form-control">
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('emp_vendor_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                @endforeach
                            </select>
                            @error('emp_vendor_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" value="{{ old('date') }}" required>
                            @error('date')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" name="amount" class="form-control" value="{{ old('amount') }}" required>
                            @error('amount')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="inc_exp_category_id" class="form-label">Category</label>
                            <select name="inc_exp_category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('inc_exp_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('inc_exp_category_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit">Add Expense</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Add Expense Modal -->
    <div class="card">
        <div class="card-body responsive-table">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Paid Amount</th>
                        <th>Remaining Amount</th>
                        <th>Category</th>
                        <th>Source</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses as $expense)
                    <tr>
                        <td>{{ $expense->id }}</td>
                        <td>{{ $expense->date }}</td>
                        <td>{{ $expense->amount }}</td>
                        <td>{{ number_format($expenseDuePaid[$expense->id] ?? 0, 2) }}</td>
                        <td>{{ number_format($expense->amount - ($expenseDuePaid[$expense->id] ?? 0), 2) }}</td>
                        <td>{{ $expense->category->name ?? '' }}</td>
                        <td>
                            @if($expense->emp_vendor_type === 'employee' && $expense->emp_vendor_id)
                                {{ optional($employees->where('id', $expense->emp_vendor_id)->first())->name }}
                            @elseif($expense->emp_vendor_type === 'vendor' && $expense->emp_vendor_id)
                                {{ optional($vendors->where('id', $expense->emp_vendor_id)->first())->name }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $expense->description }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editExpenseModal{{ $expense->id }}"><i class="fa-solid fa-pen"></i></button>
                            <form action="{{ route('admin.expense.destroy', $expense) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Expense Modals -->
@foreach($expenses as $expense)
<div class="modal fade" id="editExpenseModal{{ $expense->id }}" tabindex="-1" aria-labelledby="editExpenseModalLabel{{ $expense->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @php
                $empName = $expense->emp_vendor_type === 'employee' && $expense->emp_vendor_id ? optional($employees->where('id', $expense->emp_vendor_id)->first())->name : null;
                $vendorName = $expense->emp_vendor_type === 'vendor' && $expense->emp_vendor_id ? optional($vendors->where('id', $expense->emp_vendor_id)->first())->name : null;
            @endphp
            @if($empName)
                <div class="alert alert-info mb-0 rounded-0">Employee: <strong>{{ $empName }}</strong></div>
            @elseif($vendorName)
                <div class="alert alert-info mb-0 rounded-0">Vendor: <strong>{{ $vendorName }}</strong></div>
            @endif
            <div class="modal-header">
                <h5 class="modal-title" id="editExpenseModalLabel{{ $expense->id }}">Edit Expense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.expense.update', $expense) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Source</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="emp_vendor_type" id="editTypeEmployee{{ $expense->id }}" value="employee" {{ old('emp_vendor_type', $expense->emp_vendor_type) == 'employee' ? 'checked' : '' }}>
                                <label class="form-check-label" for="editTypeEmployee{{ $expense->id }}">Employee</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3" id="editEmployeeDropdown{{ $expense->id }}" style="display: {{ old('emp_vendor_type', $expense->emp_vendor_type) == 'employee' ? 'block' : 'none' }};">
                        <label for="edit_emp_vendor_id_employee{{ $expense->id }}" class="form-label">Employee</label>
                        <select name="emp_vendor_id" id="edit_emp_vendor_id_employee{{ $expense->id }}" class="form-control">
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('emp_vendor_id', $expense->emp_vendor_id) == $employee->id && old('emp_vendor_type', $expense->emp_vendor_type) == 'employee' ? 'selected' : '' }}>{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" name="date" class="form-control" value="{{ old('date', $expense->date) }}" required>
                        @error('date')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" name="amount" class="form-control" value="{{ old('amount', $expense->amount) }}" required>
                        @error('amount')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="inc_exp_category_id" class="form-label">Category</label>
                        <select name="inc_exp_category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('inc_exp_category_id', $expense->inc_exp_category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('inc_exp_category_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control">{{ old('description', $expense->description) }}</textarea>
                        @error('description')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit">Update Expense</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection 