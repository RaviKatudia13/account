@extends('layouts.admin')

@section('title', 'Add Payment Method')

@section('content')
<div class="container mt-4">
    <h1 class="h3 mb-4">Add Payment Method</h1>
    <form action="{{ route('admin.payment_methods.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
            @error('name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
            @error('description')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Add Payment Method</button>
        <a href="{{ route('admin.payment_methods.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 