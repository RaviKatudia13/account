@extends('layouts.admin')

@section('title', 'Edit Vendor')

@section('content')
<div class="container mt-4">
    <h1 class="h3 mb-4">Edit Vendor</h1>
    <form method="POST" action="{{ route('admin.vendors.update', $vendor) }}" style="max-width:600px;">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $vendor->name) }}" required>
            @error('name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $vendor->address) }}" required>
            @error('address')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $vendor->phone) }}" required>
            @error('phone')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="salary" class="form-label">Amount</label>
            <input type="number" step="0.01" name="salary" class="form-control" value="{{ old('salary', $vendor->salary) }}">
            @error('salary')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="vendor_category_id" class="form-label">Category</label>
            <select name="vendor_category_id" class="form-control" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $vendor->vendor_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('vendor_category_id')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <button class="btn btn-primary" type="submit">Update Vendor</button>
        <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
@endsection 