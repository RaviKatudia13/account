<div
    x-show="invoiceModal"
    x-transition
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
>
    <div @click.away="invoiceModal = false" class="bg-white w-full max-w-5xl rounded-xl shadow-xl p-6 relative overflow-y-auto max-h-[90vh]">
        <button @click="invoiceModal = false" class="absolute top-3 right-4 text-gray-500 hover:text-red-500 text-lg">
            &times;
        </button>

        @include('admin.invoices.modals._create-invoice-body')
    </div>
</div>