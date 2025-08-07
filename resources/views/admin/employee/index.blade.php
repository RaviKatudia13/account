@extends('layouts.admin')

@section('title', 'Vendors')

@section('content')
<div class="container mt-16">
    <h1 class="h3 mb-4">Employees</h1>
    
    <div x-data="employeeModalHandler()">
        <button class="btn btn-primary mb-3" @click="openAddModal()">Add Employee</button>
        <!-- Add Employee Modal -->
        <div
            x-show="showAddModal"
            x-transition
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        >
            <div @click.away="showAddModal = false" class="bg-white w-full max-w-lg rounded-xl shadow-xl p-6 relative overflow-y-auto max-h-[90vh]">
                <button type="button" @click="showAddModal = false" class="absolute top-3 right-4 text-gray-500 hover:text-red-500 text-lg">&times;</button>
                <h2 class="text-lg font-semibold mb-1">Add Employee</h2>
                <p class="text-sm text-gray-500 mb-4">Fill in the details to add a new employee.</p>
                <form class="space-y-4" method="POST" action="{{ route('admin.employees.store') }}">
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
                            <label for="number" class="text-sm font-medium block mb-1">Number</label>
                            <input type="text" class="w-full border rounded px-3 py-2 text-sm" id="number" name="number" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="address" class="text-sm font-medium block mb-1">Address</label>
                            <input type="text" class="w-full border rounded px-3 py-2 text-sm" id="address" name="address" required>
                        </div>
                        <div>
                            <label for="designation" class="text-sm font-medium block mb-1">Designation</label>
                            <input type="text" class="w-full border rounded px-3 py-2 text-sm" id="designation" name="designation" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="join_date" class="text-sm font-medium block mb-1">Join Date</label>
                            <input type="date" class="w-full border rounded px-3 py-2 text-sm" id="join_date" name="join_date" required>
                        </div>
                    </div>
                    <div>
                        <label for="employee_category_id" class="text-sm font-medium block mb-1">Category</label>
                        <select class="w-full border rounded px-3 py-2 text-sm" id="employee_category_id" name="employee_category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" class="px-4 py-2 border rounded-md text-sm" @click="showAddModal = false">Cancel</button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Save Employee</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Edit Employee Modal -->
        <div
            x-show="showEditModal"
            x-transition
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        >
            <div @click.away="showEditModal = false" class="bg-white w-full max-w-lg rounded-xl shadow-xl p-6 relative overflow-y-auto max-h-[90vh]">
                <button type="button" @click="showEditModal = false" class="absolute top-3 right-4 text-gray-500 hover:text-red-500 text-lg">&times;</button>
                <h2 class="text-lg font-semibold mb-1">Edit Employee</h2>
                <p class="text-sm text-gray-500 mb-4">Update the details of the employee.</p>
                <form class="space-y-4" :action="editActionUrl" method="POST">
                    <input type="hidden" name="_method" value="PUT">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium block mb-1">Name</label>
                            <input type="text" class="w-full border rounded px-3 py-2 text-sm" name="name" x-model="editEmployee.name" required>
                        </div>
                        <div>
                            <label class="text-sm font-medium block mb-1">Number</label>
                            <input type="text" class="w-full border rounded px-3 py-2 text-sm" name="number" x-model="editEmployee.number" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium block mb-1">Address</label>
                            <input type="text" class="w-full border rounded px-3 py-2 text-sm" name="address" x-model="editEmployee.address" required>
                        </div>
                        <div>
                            <label class="text-sm font-medium block mb-1">Designation</label>
                            <input type="text" class="w-full border rounded px-3 py-2 text-sm" name="designation" x-model="editEmployee.designation" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium block mb-1">Join Date</label>
                            <input type="date" class="w-full border rounded px-3 py-2 text-sm" name="join_date" x-model="editEmployee.join_date" required>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium block mb-1">Category</label>
                        <select class="w-full border rounded px-3 py-2 text-sm" name="employee_category_id" x-model="editEmployee.employee_category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" class="px-4 py-2 border rounded-md text-sm" @click="showEditModal = false">Cancel</button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">Update Employee</button>
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
                            <th>Number</th>
                            <th>Designation</th>
                            <th>Join Date</th>
                            <th>Category</th>
                            <th>Remaining Due</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $paymentModals = ''; @endphp
                        @foreach($employees as $employee)
                        <tr>
                            <td>{{ $employee->id }}</td>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->address }}</td>
                            <td>{{ $employee->number }}</td>
                            <td>{{ $employee->designation }}</td>
                            <td>{{ $employee->join_date }}</td>
                            <td>{{ $employee->employeeCategory->name ?? '' }}</td>
                            <td>₹{{ number_format($employee->total_remaining_amount, 2) }}
                                
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#historyModal{{ $employee->id }}"><i class="fa-solid fa-eye"></i></button>
                                <button class="btn btn-sm btn-warning" @click="openEditModal(@js([
                                    'id' => $employee->id,
                                    'name' => $employee->name,
                                    'address' => $employee->address,
                                    'number' => $employee->number,
                                    'designation' => $employee->designation,
                                    'join_date' => $employee->join_date,
                                    'employee_category_id' => $employee->employee_category_id
                                ]), '{{ route('admin.employees.update', $employee) }}')">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                                <!-- Add Payment Button -->
                                @if($employee->total_remaining_amount > 0)
                                <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#addPaymentModal{{ $employee->id }}" title="Add Payment">
                                    <i class="fa-solid fa-money-check-alt"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @php $paymentModals .= view('admin.employee._add_payment_modal', compact('employee', 'paymentMethods', 'accounts'))->render(); @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{!! $paymentModals !!}

<!-- Employee History Modals -->
@foreach($employees as $employee)
    <div class="modal fade" id="historyModal{{ $employee->id }}" tabindex="-1" aria-labelledby="historyModalLabel{{ $employee->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historyModalLabel{{ $employee->id }}">History for {{ $employee->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>History (Dues & Payments)</h6>
                    @php
                        $history = collect();
                        foreach ($employee->dues as $due) {
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
                        foreach ($employee->payments as $payment) {
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
                                        <td>
                                            @if($item['type'] === 'Payment')
                                                {{ $item['payment_mode'] ?? '-' }}
                                            @else
                                                -
                                            @endif
                                        </td>
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
function employeeModalHandler() {
    return {
        showAddModal: false,
        showEditModal: false,
        editEmployee: {
            name: '',
            number: '',
            address: '',
            designation: '',
            join_date: '',
            employee_category_id: ''
        },
        editActionUrl: '',
        openAddModal() {
            this.showAddModal = true;
        },
        openEditModal(employee, actionUrl) {
            this.editEmployee = {...employee};
            this.editActionUrl = actionUrl;
            this.showEditModal = true;
        }
    }
}
</script>
@endsection 