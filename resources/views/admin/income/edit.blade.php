@extends('layouts.admin')

@section('title', 'Edit Income')

@section('content')
<div class="container mt-4" style="max-width:500px;">
    <h1 class="h3 mb-4">Edit Income</h1>
    <form method="POST" action="{{ route('admin.income.update', $income) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" class="form-control" value="{{ old('date', $income->date) }}" required>
            @error('date')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" name="amount" class="form-control" value="{{ old('amount', $income->amount) }}" required>
            @error('amount')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select name="category_id" class="form-control" required>
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $income->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ old('description', $income->description) }}</textarea>
            @error('description')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <button class="btn btn-primary" type="submit">Update Income</button>
        <a href="{{ route('admin.income.index') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
@endsection 