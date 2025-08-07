@extends('layouts.admin')

@section('title', 'Add Income')

@section('content')
<div class="container mt-4" style="max-width:500px;">
    <h1 class="h3 mb-4">Add Income</h1>
    <form method="POST" action="{{ route('admin.income.store') }}">
        @csrf
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
            <label for="category_id" class="form-label">Category</label>
            <select name="category_id" class="form-control" required>
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')
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
        <div class="mb-3">
            <label class="form-label">Source</label>
            <div class="d-flex gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="emp_vendor_type" id="addTypeVendor" value="vendor" {{ old('emp_vendor_type') == 'vendor' ? 'checked' : '' }}>
                    <label class="form-check-label" for="addTypeVendor">Vendor</label>
                </div>
            </div>
            @error('emp_vendor_type')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3" id="addEmployeeDropdown" style="display: {{ old('emp_vendor_type', 'employee') == 'employee' ? 'block' : 'none' }};">
            <label for="add_emp_vendor_id_employee" class="form-label">Employee</label>
            <select name="emp_vendor_id" id="add_emp_vendor_id_employee" class="form-control">
                <option value="">Select Employee</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ old('emp_vendor_id') == $employee->id && old('emp_vendor_type', 'employee') == 'employee' ? 'selected' : '' }}>{{ $employee->name }}</option>
                @endforeach
            </select>
            @if(old('emp_vendor_type', 'employee') == 'employee')
                @error('emp_vendor_id')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            @endif
        </div>
        <div class="mb-3" id="addVendorDropdown" style="display: {{ old('emp_vendor_type') == 'vendor' ? 'block' : 'none' }};">
            <label for="add_emp_vendor_id_vendor" class="form-label">Vendor</label>
            <select name="emp_vendor_id" id="add_emp_vendor_id_vendor" class="form-control">
                <option value="">Select Vendor</option>
                @foreach($vendors as $vendor)
                    <option value="{{ $vendor->id }}" {{ old('emp_vendor_id') == $vendor->id && old('emp_vendor_type') == 'vendor' ? 'selected' : '' }}>{{ $vendor->name }}</option>
                @endforeach
            </select>
            @if(old('emp_vendor_type') == 'vendor')
                @error('emp_vendor_id')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            @endif
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            function toggleEmpVendorDropdown(type) {
                document.getElementById('addEmployeeDropdown').style.display = type === 'employee' ? 'block' : 'none';
                document.getElementById('addVendorDropdown').style.display = type === 'vendor' ? 'block' : 'none';
            }
            document.querySelectorAll('input[name="emp_vendor_type"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    toggleEmpVendorDropdown(this.value);
                });
            });
        });
        </script>
        <button class="btn btn-primary" type="submit">Add Income</button>
        <a href="{{ route('admin.income.index') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
@endsection 