@extends('layouts.admin')

@section('title', 'Edit Employee')

@section('content')
<div x-data="{ showEditModal: true }">
    <div
        x-show="showEditModal"
        x-transition
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
        <div @click.away="showEditModal = false" class="bg-white w-full max-w-lg rounded-xl shadow-xl p-6 relative overflow-y-auto max-h-[90vh]">
            <button type="button" @click="showEditModal = false" class="absolute top-3 right-4 text-gray-500 hover:text-red-500 text-lg">&times;</button>
            <h2 class="text-lg font-semibold mb-1">Edit Employee</h2>
            <p class="text-sm text-gray-500 mb-4">Update the details of the employee.</p>
            <form class="space-y-4" method="POST" action="{{ route('admin.employees.update', $employee) }}">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="text-sm font-medium block mb-1">Name</label>
                        <input type="text" class="w-full border rounded px-3 py-2 text-sm" id="name" name="name" value="{{ old('name', $employee->name) }}" required>
                        @error('name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="number" class="text-sm font-medium block mb-1">Number</label>
                        <input type="text" class="w-full border rounded px-3 py-2 text-sm" id="number" name="number" value="{{ old('number', $employee->number) }}" required>
                        @error('number')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="address" class="text-sm font-medium block mb-1">Address</label>
                        <input type="text" class="w-full border rounded px-3 py-2 text-sm" id="address" name="address" value="{{ old('address', $employee->address) }}" required>
                        @error('address')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="designation" class="text-sm font-medium block mb-1">Designation</label>
                        <input type="text" class="w-full border rounded px-3 py-2 text-sm" id="designation" name="designation" value="{{ old('designation', $employee->designation) }}" required>
                        @error('designation')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="join_date" class="text-sm font-medium block mb-1">Join Date</label>
                        <input type="date" class="w-full border rounded px-3 py-2 text-sm" id="join_date" name="join_date" value="{{ old('join_date', $employee->join_date) }}" required>
                        @error('join_date')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div>
                    <label for="employee_category_id" class="text-sm font-medium block mb-1">Category</label>
                    <select class="w-full border rounded px-3 py-2 text-sm" id="employee_category_id" name="employee_category_id" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $employee->employee_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('employee_category_id')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" class="px-4 py-2 border rounded-md text-sm" @click="showEditModal = false">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Update Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 