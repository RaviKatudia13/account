@extends('layouts.admin')

@section('title', 'Edit Vendor Category')

@section('content')
<div class="container mt-4">
    <h1 class="h3 mb-4">Edit Vendor Category</h1>
    <form method="POST" action="{{ route('admin.vendor-categories.update', $category) }}" style="max-width:400px;">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Category Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
            @error('name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <button class="btn btn-primary" type="submit">Update Category</button>
        <a href="{{ route('admin.vendor-categories.index') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
@endsection 
