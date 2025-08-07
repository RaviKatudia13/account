@extends('layouts.admin')

@section('title', 'Clients')

@section('content')
<div class="mb-4 mt-16">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold">Clients</h1>
            <p class="text-muted">Manage your client information and billing</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-file-invoice"></i> View Invoices
            </a>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClientModal">
                <i class="fas fa-plus"></i> Add Client
            </button>
        </div>
    </div>

    <div class="bg-white rounded shadow-sm border p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <input type="text" placeholder="🔍 Search clients..." class="form-control w-50">
            <div class="btn-group">
                <button class="btn btn-sm btn-outline-secondary">All</button>
                <button class="btn btn-sm btn-outline-secondary">With Dues</button>
                <button class="btn btn-sm btn-outline-secondary">No Dues</button>
            </div>
        </div>
        <div class="table-responsive responsive-table">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Mobile</th>
                        <th>DUE Amount</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clients as $client)
                    <tr>
                        <td class="fw-semibold">{{ $client->name }}</td>
                        <td>{{ $client->company_name }}</td>
                        <td>{{ $client->mobile }}</td>
                        <td>{{ $client->dueAmount() }}</td>
                        <td>{{ $client->category ? $client->category->name : '' }}</td>
                        <td>
                            <div class="table-action-btn-group">
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#viewClientModal{{ $client->id }}"><i class="fa fa-eye"></i></button>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editClientModal{{ $client->id }}"><i class="fa fa-pen"></i></button>
                                <form action="{{ route('admin.users.destroy', $client->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this client?')"><i class="fa fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No clients found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @foreach($clients as $client)
    <!-- View Client Modal -->
    <div class="modal fade" id="viewClientModal{{ $client->id }}" tabindex="-1" aria-labelledby="viewClientModalLabel{{ $client->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewClientModalLabel{{ $client->id }}">Client Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <p><strong>Name:</strong> {{ $client->name }}</p>
                            <p><strong>Company:</strong> {{ $client->company_name }}</p>
                            <p><strong>Mobile:</strong> {{ $client->mobile }}</p>
                            <p><strong>Email:</strong> {{ $client->email }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <p><strong>Address:</strong> {{ $client->address }}</p>
                            <p><strong>GST Registered:</strong> {{ $client->gst_registered ? 'Yes' : 'No' }}</p>
                            <p><strong>GSTIN:</strong> {{ $client->gstin }}</p>
                            <p><strong>Payment Mode:</strong> {{ $client->payment_mode }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Client Modal -->
    <div class="modal fade" id="editClientModal{{ $client->id }}" tabindex="-1" aria-labelledby="editClientModalLabel{{ $client->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClientModalLabel{{ $client->id }}">Edit Client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>  
                <form method="POST" action="{{ route('admin.users.update', $client->id) }}">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Client Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $client->name }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Company Name</label>
                                <input type="text" name="company_name" class="form-control" value="{{ $client->company_name }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="mobile" class="form-control" value="{{ $client->mobile }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $client->email }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-control" value="{{ $client->address }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">GSTIN</label>
                                <input type="text" name="gstin" class="form-control" value="{{ $client->gstin }}">
                                <div class="form-check mt-2">
                                    <input type="hidden" name="gst_registered" value="0">
                                    <input hidden class="form-check-input" type="checkbox" name="gst_registered" value="1" id="edit_gst_registered{{$client->id}}" {{ $client->gst_registered ? 'checked' : '' }}>
                                    <label hidden class="form-check-label" for="edit_gst_registered{{$client->id}}">GST Registered</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-select">
                                    <option value="" disabled selected>Select category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ (isset($client) && $client->category_id == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Add Client Modal -->
    <div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClientModalLabel">Add New Client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Client Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter client name" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Company Name</label>
                                <input type="text" name="company_name" class="form-control" placeholder="Enter company name">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="mobile" class="form-control" placeholder="Enter mobile number">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter email address">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-control" placeholder="Enter address">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">GSTIN</label>
                                <input type="text" name="gstin" class="form-control" placeholder="Enter GSTIN">
                            </div>
                                    <input type="hidden" name="gst_registered" value="0">
                                    <input class="form-check-input" type="checkbox" name="gst_registered" value="1" id="add_gst_registered" hidden>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-select">
                                    <option value="" disabled selected>Select category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Client</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

@endsection
