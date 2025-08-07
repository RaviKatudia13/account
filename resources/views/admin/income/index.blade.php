@extends('layouts.admin')

@section('title', 'Income List')

@section('content')
<div class="container mt-16" x-data="{ showAddModal: false }">
    <h1 class="h3 mb-4 fw-bold">Income List</h1>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addIncomeModal"><i class="fa fa-plus"></i> Add Income</button>
    <!-- Add Income Modal -->
    <div class="modal fade" id="addIncomeModal" tabindex="-1" aria-labelledby="addIncomeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addIncomeModalLabel">Add Income</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.income.store') }}">
                    @csrf
                    <div class="modal-body">
                    <div class="mb-3">
                            <!-- <label class="form-label">Source</label> -->
                          
                                    <input class="form-check-input" type="radio" name="emp_vendor_type" id="addTypeVendor" value="vendor" checked disabled hidden>
                                    <label class="form-check-label" for="addTypeVendor" hidden>Vendor</label>
                            <input type="hidden" name="emp_vendor_type" value="vendor">
                        </div>
                        <div class="mb-3" id="addVendorDropdown">
                            <label for="add_emp_vendor_id_vendor" class="form-label">Vendor</label>
                            <select name="emp_vendor_id" id="add_emp_vendor_id_vendor" class="form-control">
                                <option value="">Select Vendor</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" {{ old('emp_vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
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
                        <button class="btn btn-primary" type="submit">Add Income</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Add Income Modal -->
    <div class="card">
        <div class="card-body responsive-table">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Employee / Vendor Name</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($incomes as $income)
                    <tr>
                        <td>{{ $income->id }}</td>
                        <td>
                            @if($income->emp_vendor_type === 'vendor' && $income->emp_vendor_id)
                                {{ optional($vendors->where('id', $income->emp_vendor_id)->first())->name }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $income->date }}</td>
                        <td>{{ $income->amount }}</td>
                        <td>{{ $income->category->name ?? '' }}</td>
                        
                        <td>{{ $income->description }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editIncomeModal{{ $income->id }}"><i class="fa-solid fa-pen"></i></button>
                            <form action="{{ route('admin.income.destroy', $income) }}" method="POST" style="display:inline-block;">
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

<!-- Edit Income Modals -->
@foreach($incomes as $income)
<div class="modal fade" id="editIncomeModal{{ $income->id }}" tabindex="-1" aria-labelledby="editIncomeModalLabel{{ $income->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @php
                $vendorName = $income->emp_vendor_type === 'vendor' && $income->emp_vendor_id ? optional($vendors->find($income->emp_vendor_id))->name : null;
            @endphp
            @if($vendorName)
                <div class="alert alert-info mb-0 rounded-0">Vendor: <strong>{{ $vendorName }}</strong></div>
            @endif
            <div class="modal-header">
                <h5 class="modal-title" id="editIncomeModalLabel{{ $income->id }}">Edit Income</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.income.update', $income) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                <div class="mb-3">
                        <label class="form-label">Source</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="emp_vendor_type" id="editTypeVendor{{ $income->id }}" value="vendor" checked disabled>
                                <label class="form-check-label" for="editTypeVendor{{ $income->id }}">Vendor</label>
                            </div>
                        </div>
                        <input type="hidden" name="emp_vendor_type" value="vendor">
                    </div>
                    <div class="mb-3" id="editVendorDropdown{{ $income->id }}">
                        <label for="edit_emp_vendor_id_vendor{{ $income->id }}" class="form-label">Vendor</label>
                        <select name="emp_vendor_id" id="edit_emp_vendor_id_vendor{{ $income->id }}" class="form-control">
                            <option value="">Select Vendor</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ old('emp_vendor_id', $income->emp_vendor_id) == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                        @error('emp_vendor_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
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
                        <label for="inc_exp_category_id" class="form-label">Category</label>
                        <select name="inc_exp_category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('inc_exp_category_id', $income->inc_exp_category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('inc_exp_category_id')
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit">Update Income</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection 