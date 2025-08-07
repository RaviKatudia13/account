@extends('layouts.admin')

@section('title', 'Employee Categories')

@section('content')
<div class="container mt-16">
    <h1 class="h3 mb-4 fw-bold">Employee Categories</h1>
    <form method="POST" action="{{ route('admin.employee-categories.store') }}" class="mb-4">
        @csrf
        <div class="input-group mb-3" style="max-width:400px;">
            <input type="text" name="name" class="form-control" placeholder="New Category Name" required>
            <button class="btn btn-primary" type="submit">Add Category</button>
        </div>
        @error('name')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </form>
    <div class="card">
        <div class="card-body responsive-table">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('admin.employee-categories.edit', $category) }}" class="btn btn-sm btn-warning">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.employee-categories.destroy', $category) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 