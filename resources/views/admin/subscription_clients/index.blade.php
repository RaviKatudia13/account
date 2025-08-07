@extends('layouts.admin')

@section('content')
    <div class="container mt-16">
        <h1 class="h3 mb-4 fw-bold">Subscription Clients</h1>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addClientModal">Add New Client</button>
        <div class="card">
            <div class="card-body responsive-table">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Company Name</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                            <tr>
                                <td>{{ $client->id }}</td>
                                <td>{{ $client->name }}</td>
                                <td>{{ $client->company_name }}</td>
                                <td>{{ $client->mobile }}</td>
                                <td>{{ $client->email }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#viewClientModal{{ $client->id }}"><i class="fa fa-eye"></i></button>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editClientModal{{ $client->id }}"><i class="fa fa-pen"></i></button>
                                    <form action="#" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- Pagination if needed --}}
                {{-- {{ $clients->links() }} --}}
            </div>
        </div>
    </div>
    <!-- All Edit Modals Here -->
    @foreach($clients as $client)
        <div class="modal fade" id="editClientModal{{ $client->id }}" tabindex="-1" aria-labelledby="editClientModalLabel{{ $client->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editClientModalLabel{{ $client->id }}">Edit Subscription Client</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('admin.subscription-clients.update', $client->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Client Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $client->name }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" name="company_name" class="form-control" value="{{ $client->company_name }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Mobile Number</label>
                                    <input type="text" name="mobile" class="form-control" value="{{ $client->mobile }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ $client->email }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Address</label>
                                    <input type="text" name="address" class="form-control" value="{{ $client->address }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">GSTIN</label>
                                    <input type="text" name="gstin" class="form-control" value="{{ $client->gstin }}">
                                    <div class="form-check mt-2">
                                        <input type="hidden" name="gst_registered" value="0">
                                        <input hidden class="form-check-input" type="checkbox" name="gst_registered" value="1" id="edit_gst_registered{{ $client->id }}" {{ $client->gst_registered ? 'checked' : '' }}>
                                        <label hidden class="form-check-label" for="edit_gst_registered{{ $client->id }}"></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" class="w-full border rounded px-3 py-2 text-sm" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $client->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
    <!-- View Client Modals -->
    @foreach($clients as $client)
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
    @endforeach
    <!-- Add Client Modal -->
    <div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClientModalLabel">Add New Client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="#">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Client Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter client name"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Company Name</label>
                                <input type="text" name="company_name" class="form-control"
                                    placeholder="Enter company name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="mobile" class="form-control" placeholder="Enter mobile number">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter email address">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-control" placeholder="Enter address">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">GSTIN</label>
                                <input type="text" name="gstin" class="form-control" placeholder="Enter GSTIN">
                                <div class="form-check mt-2">
                                    <input type="hidden" name="gst_registered" value="0">
                                    <input hidden class="form-check-input" type="checkbox" name="gst_registered" value="1"
                                        id="add_gst_registered">
                                    <label hidden class="form-check-label" for="add_gst_registered"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="w-full border rounded px-3 py-2 text-sm" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Client</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection