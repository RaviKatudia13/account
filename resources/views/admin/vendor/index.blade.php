@extends('layouts.admin')

@section('title', 'Vendors')

@section('content')
<div class="container mt-16">
    <h1 class="h3 mb-4 fw-bold">Vendors</h1>
    
    <div x-data="vendorModalHandler()">
        <button class="btn btn-primary mb-3" @click="openAddModal()">Add Vendor</button>
        <!-- Add Vendor Modal -->
        <div
            x-show="showAddModal"
            x-transition
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        >
            <div @click.away="showAddModal = false" class="bg-white w-full max-w-lg rounded-xl shadow-xl p-6 relative overflow-y-auto max-h-[90vh]">
                <button type="button" @click="showAddModal = false" class="absolute top-3 right-4 text-gray-500 hover:text-red-500 text-lg">&times;</button>
                <h2 class="text-lg font-semibold mb-1">Add Vendor</h2>
                <p class="text-sm text-gray-500 mb-4">Fill in the details to add a new vendor.</p>
                <form class="space-y-4" method="POST" action="{{ route('admin.vendors.store') }}">
                    @csrf
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <strong>Please fix the following errors:</strong>
                            <ul class="list-disc list-inside mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="text-sm font-medium block mb-1">Name</label>
                            <input type="text" class="w-full border rounded px-3 py-2 text-sm" id="name" name="name" required>
                        </div>
                        <div>
                            <label for="phone" class="text-sm font-medium block mb-1">Phone</label>
                            <input type="text" class="w-full border rounded px-3 py-2 text-sm" id="phone" name="phone" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="address" class="text-sm font-medium block mb-1">Address</label>
                            <input type="text" class="w-full border rounded px-3 py-2 text-sm" id="address" name="address" required>
                        </div>
                    </div>
                    <div>
                        <label for="vendor_category_id" class="text-sm font-medium block mb-1">Category</label>
                        <select class="w-full border rounded px-3 py-2 text-sm" id="vendor_category_id" name="vendor_category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" class="px-4 py-2 border rounded-md text-sm" @click="showAddModal = false">Cancel</button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Save Vendor</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Edit Vendor Modal -->
        <div
            x-show="showEditModal"
            x-transition
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        >
            <div @click.away="showEditModal = false" class="bg-white w-full max-w-lg rounded-xl shadow-xl p-6 relative overflow-y-auto max-h-[90vh]">
                <button type="button" @click="showEditModal = false" class="absolute top-3 right-4 text-gray-500 hover:text-red-500 text-lg">&times;</button>
                <h2 class="text-lg font-semibold mb-1">Edit Vendor</h2>
                <p class="text-sm text-gray-500 mb-4">Update the details of the vendor.</p>
                <form class="space-y-4" :action="editActionUrl" method="POST">
                    <input type="hidden" name="_method" value="PUT">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium block mb-1">Name</label>
                            <input type="text" class="w-full border rounded px-3 py-2 text-sm" name="name" x-model="editVendor.name" required>
                        </div>
                        <div>
                            <label class="text-sm font-medium block mb-1">Phone</label>
                            <input type="text" class="w-full border rounded px-3 py-2 text-sm" name="phone" x-model="editVendor.phone" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium block mb-1">Address</label>
                            <input type="text" class="w-full border rounded px-3 py-2 text-sm" name="address" x-model="editVendor.address" required>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium block mb-1">Category</label>
                        <select class="w-full border rounded px-3 py-2 text-sm" name="vendor_category_id" x-model="editVendor.vendor_category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" class="px-4 py-2 border rounded-md text-sm" @click="showEditModal = false">Cancel</button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Update Vendor</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body responsive-table">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Category</th>
                            <th>Remaining Due</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        @php $paymentModals = ''; @endphp
                        @foreach($vendors as $vendor)
                        <tr>
                            <td>{{ $vendor->id }}</td>
                            <td>{{ $vendor->name }}</td>
                            <td>{{ $vendor->address }}</td>
                            <td>{{ $vendor->phone }}</td>
                            <td>{{ $vendor->vendorCategory->name ?? '' }}</td>
                            <td>₹{{ number_format($vendor->total_remaining_amount, 2) }}
                            </td>
                            <td>
                                <div class="table-action-btn-group">
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#historyModal{{ $vendor->id }}"><i class="fa fa-eye"></i></button>
                                    <a href="{{ route('admin.vendors.edit', $vendor) }}" class="btn btn-sm btn-warning"><i class="fa fa-pen"></i></a>
                                    <form action="{{ route('admin.vendors.destroy', $vendor) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this vendor?')"><i class="fa fa-trash"></i></button>
                                    </form>
                                    @if($vendor->total_remaining_amount > 0)
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addPaymentModal{{ $vendor->id }}"><i class="fa fa-money-check-alt"></i></button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @php $paymentModals .= view('admin.vendor._add_payment_modal', compact('vendor', 'paymentMethods', 'accounts'))->render(); @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{!! $paymentModals !!}

<!-- Vendor History Modals -->
@foreach($vendors as $vendor)
    <div class="modal fade" id="historyModal{{ $vendor->id }}" tabindex="-1" aria-labelledby="historyModalLabel{{ $vendor->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historyModalLabel{{ $vendor->id }}">History for {{ $vendor->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>History (Dues & Payments)</h6>
                    @php
                        $history = collect();
                        foreach ($vendor->dues as $due) {
                            $history->push([
                                'id' => $due->id,
                                'type' => 'Due',
                                'date' => $due->date ?? ($due->created_at ? $due->created_at->format('Y-m-d') : null),
                                'date_time' => $due->created_at ? $due->created_at->format('Y-m-d H:i') : ($due->date ?? null),
                                'amount' => $due->total_amount,
                                'status' => $due->status ?? '-',
                                'description' => $due->description ?? '-',
                                'account' => null,
                                'payment_mode' => '-',
                            ]);
                        }
                        foreach ($vendor->payments as $payment) {
                            $history->push([
                                'id' => $payment->id,
                                'type' => 'Payment',
                                'date' => $payment->payment_date ?? ($payment->created_at ? $payment->created_at->format('Y-m-d') : null),
                                'date_time' => $payment->created_at ? $payment->created_at->format('Y-m-d H:i') : ($payment->payment_date ?? null),
                                'amount' => $payment->amount,
                                'status' => '-',
                                'description' => $payment->remarks ?? '-',
                                'account' => $payment->account ? $payment->account->display_name : '-',
                                'payment_mode' => $payment->paymentMethod->name ?? '-',
                            ]);
                        }
                        $history = $history->sortBy('date_time');
                        $totalCredit = $history->where('type', 'Payment')->sum('amount');
                        $totalDebit = $history->where('type', 'Due')->sum('amount');
                    @endphp
                    @if($history->count())
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Description / Remarks</th>
                                    <th>Payment Mode</th>
                                    <th>Account</th>
                                    <th>Credit</th>
                                    <th>Debit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($history as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item['date_time'] ?? $item['date'] }}</td>
                                        <td>{{ $item['description'] }}</td>
                                        <td>{{ $item['payment_mode'] }}</td>
                                        <td>
                                            @if($item['type'] === 'Payment')
                                                {{ $item['account'] ?? '-' }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>@if($item['type'] === 'Payment'){{ $item['amount'] }}@endif</td>
                                        <td>@if($item['type'] === 'Due'){{ $item['amount'] }}@endif</td>
                                        
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5">Total</th>
                                    <th>{{ $totalCredit }}</th>
                                    <th>{{ $totalDebit }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    @else
                        <p>No history found.</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script>
function vendorModalHandler() {
    return {
        showAddModal: false,
        showEditModal: false,
        editVendor: {
            name: '',
            address: '',
            phone: '',
            vendor_category_id: ''
        },
        editActionUrl: '',
        openAddModal() {
            this.showAddModal = true;
        },
        openEditModal(vendor, actionUrl) {
            this.editVendor = {...vendor};
            this.editActionUrl = actionUrl;
            this.showEditModal = true;
        }
    }
}
</script>
@endsection 