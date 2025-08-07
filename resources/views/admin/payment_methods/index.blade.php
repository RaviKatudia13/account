@extends('layouts.admin')

@section('title', 'Payment Methods')

@section('content')
<div class="container mt-16">
    <h1 class="h3 mb-4 fw-bold">Payment Methods</h1>
    <a href="{{ route('admin.payment_methods.create') }}" class="btn btn-primary mb-3">Add Payment Method</a>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paymentMethods as $method)
                    <tr>
                        <td>{{ $method->id }}</td>
                        <td>{{ $method->name }}</td>
                        <td>{{ $method->description }}</td>
                        <td>
                            <a href="{{ route('admin.payment_methods.edit', $method) }}" class="btn btn-sm btn-warning">
                                <i class="fa-solid fa-pen"></i> 
                            </a>
                            <form action="{{ route('admin.payment_methods.destroy', $method) }}" method="POST" style="display:inline-block;">
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