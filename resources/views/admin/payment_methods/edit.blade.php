@extends('layouts.admin')

@section('title', 'Edit Payment Method')

@section('content')
<div class="container mt-4">
    <h1 class="h3 mb-4">Edit Payment Method</h1>
    <form action="{{ route('admin.payment_methods.update', $paymentMethod) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $paymentMethod->name) }}">
            @error('name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ old('description', $paymentMethod->description) }}</textarea>
            @error('description')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Update Payment Method</button>
        <a href="{{ route('admin.payment_methods.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 