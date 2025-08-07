@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl',
    'id' => null
])

@php
$maxWidth = [
    'sm' => 'modal-sm',
    'md' => '',
    'lg' => 'modal-lg',
    'xl' => 'modal-xl',
    '2xl' => 'modal-xl',
][$maxWidth];

$modalId = $id ?? 'modal-' . $name;
@endphp

<!-- Bootstrap Modal -->
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}-label" aria-hidden="true">
    <div class="modal-dialog {{ $maxWidth }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $modalId }}-label">
                    {{ $attributes->get('title', 'Modal Title') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            @if($attributes->has('footer'))
                <div class="modal-footer">
                    {{ $attributes->get('footer') }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- JavaScript for modal functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap modals
    var modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        // Ensure Bootstrap is loaded
        if (typeof bootstrap !== 'undefined') {
            new bootstrap.Modal(modal);
        }
    });
    
    // Custom modal trigger function
    window.openModal = function(modalId) {
        var modal = document.getElementById(modalId);
        if (modal && typeof bootstrap !== 'undefined') {
            var bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        }
    };
    
    // Custom modal close function
    window.closeModal = function(modalId) {
        var modal = document.getElementById(modalId);
        if (modal && typeof bootstrap !== 'undefined') {
            var bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) {
                bsModal.hide();
            }
        }
    };
    
    // Handle modal events
    modals.forEach(function(modal) {
        modal.addEventListener('hidden.bs.modal', function() {
            // Clean up any form data or reset state
            var forms = modal.querySelectorAll('form');
            forms.forEach(function(form) {
                form.reset();
            });
            
            // Clear any error messages
            var alerts = modal.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.remove();
            });
        });
    });
});
</script>
