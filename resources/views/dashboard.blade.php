@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-md p-6">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-6">Dashboard</h1>
            <button onclick="resetDashboardLayout()" class="mb-4 bg-gray-200 dark:bg-gray-800 text-gray-800 dark:text-gray-200 px-3 py-1 rounded">Reset Layout</button>
            <div id="dashboard-widgets" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="widget" data-id="welcome">
                    <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-blue-900 dark:text-blue-100">Welcome!</h3>
                                <p class="text-blue-700 dark:text-blue-200">{{ Auth::user()->name }}</p>
                            </div>
                        </div>
                        <button onclick="toggleWidget('welcome')" class="mt-2 text-xs text-blue-600 dark:text-blue-200 underline">Hide</button>
                    </div>
                </div>
                <div class="widget" data-id="quick-actions">
                    <div class="bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-green-900 dark:text-green-100 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="{{ route('admin.dashboard') }}" class="block w-full bg-green-600 hover:bg-green-700 dark:bg-green-800 dark:hover:bg-green-900 text-white font-medium py-2 px-4 rounded-md text-center transition duration-200">
                                Go to Admin Panel
                            </a>
                            <a href="{{ route('profile.edit') }}" class="block w-full bg-green-500 hover:bg-green-600 dark:bg-green-700 dark:hover:bg-green-800 text-white font-medium py-2 px-4 rounded-md text-center transition duration-200">
                                Edit Profile
                            </a>
                        </div>
                        <button onclick="toggleWidget('quick-actions')" class="mt-2 text-xs text-green-600 dark:text-green-200 underline">Hide</button>
                    </div>
                </div>
                <div class="widget" data-id="stats">
                    <div class="bg-purple-50 dark:bg-purple-900 border border-purple-200 dark:border-purple-700 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-purple-900 dark:text-purple-100 mb-4">Your Account</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-purple-700 dark:text-purple-200">Email:</span>
                                <span class="text-purple-900 dark:text-purple-100 font-medium">{{ Auth::user()->email }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-purple-700 dark:text-purple-200">Member since:</span>
                                <span class="text-purple-900 dark:text-purple-100 font-medium">{{ Auth::user()->created_at->format('M Y') }}</span>
                            </div>
                        </div>
                        <button onclick="toggleWidget('stats')" class="mt-2 text-xs text-purple-600 dark:text-purple-200 underline">Hide</button>
                    </div>
                </div>
            </div>
            <div class="mt-8">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4">Recent Activity</h2>
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
                    <p class="text-gray-600 dark:text-gray-200">No recent activity to display.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
// Drag and drop, show/hide, and persist layout
const widgetOrderKey = 'dashboardWidgetOrder';
const widgetVisibilityKey = 'dashboardWidgetVisibility';
function saveWidgetOrder() {
    const order = Array.from(document.querySelectorAll('.widget')).map(w => w.dataset.id);
    localStorage.setItem(widgetOrderKey, JSON.stringify(order));
}
function saveWidgetVisibility() {
    const vis = {};
    document.querySelectorAll('.widget').forEach(w => {
        vis[w.dataset.id] = !w.classList.contains('hidden');
    });
    localStorage.setItem(widgetVisibilityKey, JSON.stringify(vis));
}
function loadDashboardLayout() {
    const order = JSON.parse(localStorage.getItem(widgetOrderKey) || '[]');
    const vis = JSON.parse(localStorage.getItem(widgetVisibilityKey) || '{}');
    const container = document.getElementById('dashboard-widgets');
    if (order.length) {
        order.forEach(id => {
            const widget = container.querySelector(`.widget[data-id="${id}"]`);
            if (widget) container.appendChild(widget);
        });
    }
    Object.entries(vis).forEach(([id, visible]) => {
        const widget = container.querySelector(`.widget[data-id="${id}"]`);
        if (widget) widget.classList.toggle('hidden', !visible);
    });
}
function toggleWidget(id) {
    const widget = document.querySelector(`.widget[data-id="${id}"]`);
    if (widget) {
        widget.classList.toggle('hidden');
        saveWidgetVisibility();
    }
}
function resetDashboardLayout() {
    localStorage.removeItem(widgetOrderKey);
    localStorage.removeItem(widgetVisibilityKey);
    location.reload();
}
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardLayout();
    // Simple drag and drop
    let dragged;
    document.querySelectorAll('.widget').forEach(widget => {
        widget.draggable = true;
        widget.addEventListener('dragstart', e => {
            dragged = widget;
            e.dataTransfer.effectAllowed = 'move';
        });
        widget.addEventListener('dragover', e => {
            e.preventDefault();
        });
        widget.addEventListener('drop', e => {
            e.preventDefault();
            if (dragged && dragged !== widget) {
                widget.parentNode.insertBefore(dragged, widget.nextSibling);
                saveWidgetOrder();
            }
        });
    });
});
</script>
@endsection