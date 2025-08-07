<!-- Sidebar Partial -->
<aside class="left-sidebar" id="sidebar">
    
    <!-- Sidebar Header -->
    <div class="p-4">   
        <div class="text-center mb-6">
            <span class="inline-block bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full p-6 shadow-lg transition-all">
                <i class="fa-solid fa-cube text-2xl text-white"></i>
            </span>
        </div>
        <div class="sidebar-title">Admin Panel</div>
    </div>
    
    <!-- Scrollable Navigation Area -->
    <div class="scroll-sidebar flex-1 overflow-y-auto h-full">
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i> Dashboard
            </a>
            <div class="sidebar-dropdown dropdown">
                <div class="sidebar-link dropdown-toggle1" onclick="this.parentElement.classList.toggle('open')">
                    <span><i class="fa-solid fa-tags"></i> Category</span>
                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                </div>
                <div class="dropdown-menu">
                    <a class="dropdown-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                        <i class="fa-solid fa-tags"></i> Manage Categories
                    </a>
                    <a class="dropdown-item {{ request()->routeIs('admin.vendor-categories.*') ? 'active' : '' }}" href="{{ route('admin.vendor-categories.index') }}">
                        <i class="fa-solid fa-list"></i> Vendor Category
                    </a>
                    <a class="dropdown-item {{ request()->routeIs('admin.employee-categories.*') ? 'active' : '' }}" href="{{ route('admin.employee-categories.index') }}">
                        <i class="fa-solid fa-list"></i> Employee Category
                    </a>
                    <a class="dropdown-item {{ request()->routeIs('admin.inc-exp-category.*') ? 'active' : '' }}" href="{{ route('admin.inc-exp-category.index') }}">
                        <i class="fa-solid fa-list"></i> Income/Expense Category
                    </a>
                </div>
            </div>
            <div class="sidebar-dropdown dropdown">
                <div class="sidebar-link dropdown-toggle1" onclick="this.parentElement.classList.toggle('open')">
                    <span><i class="fa-solid fa-users-gear"></i> Manage Clients</span>
                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                </div>
                <div class="dropdown-menu">
                    <a class="dropdown-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="fa-solid fa-user-group"></i> Clients List
                    </a>
                    <a class="dropdown-item {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}" href="{{ route('admin.invoices.index') }}">
                        <i class="fa-solid fa-file-invoice-dollar"></i> Invoices
                    </a>
                </div>
            </div>
            <div class="sidebar-dropdown dropdown">
                <div class="sidebar-link dropdown-toggle1" onclick="this.parentElement.classList.toggle('open')">
                    <span><i class="fa-solid fa-repeat"></i> Subscriptions</span>
                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                </div>
                <div class="dropdown-menu">
                    <a class="dropdown-item {{ request()->routeIs('admin.subscription-clients.*') ? 'active' : '' }}" href="{{ route('admin.subscription-clients.index') }}">
                        <i class="fa-solid fa-user-group"></i> Subscription Clients
                    </a>
                    <a class="dropdown-item {{ request()->routeIs('admin.subscription-client-payments.*') ? 'active' : '' }}" href="{{ route('admin.subscription-client-payments.index') }}">
                        <i class="fa-solid fa-money-bill"></i> Subscription Client Payments
                    </a>
                </div>
            </div>
            <div class="sidebar-dropdown dropdown">
                <div class="sidebar-link dropdown-toggle1" onclick="this.parentElement.classList.toggle('open')">
                    <span><i class="fa-solid fa-briefcase"></i> Manage Vendor</span>
                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                </div>
                <div class="dropdown-menu">
                    <a class="dropdown-item {{ request()->routeIs('admin.vendors.*') ? 'active' : '' }}" href="{{ route('admin.vendors.index') }}">
                        <i class="fa-solid fa-user-tie"></i> Vendor List
                    </a>
                    <a class="dropdown-item {{ request()->routeIs('admin.manage-due') ? 'active' : '' }}" href="{{ route('admin.manage-due') }}">
                        <i class="fa-solid fa-money-bill-wave"></i> Vendor Due
                    </a>
                </div>
            </div>
            <div class="sidebar-dropdown dropdown">
                <div class="sidebar-link dropdown-toggle1" onclick="this.parentElement.classList.toggle('open')">
                    <span><i class="fa-solid fa-user"></i> Manage Employee</span>
                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                </div>
                <div class="dropdown-menu">
                    <a class="dropdown-item {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}" href="{{ route('admin.employees.index') }}">
                        <i class="fa-solid fa-user"></i> Employee List
                    </a>
                    <a class="dropdown-item {{ request()->routeIs('admin.employee-due') ? 'active' : '' }}" href="{{ route('admin.employee-due') }}">
                        <i class="fa-solid fa-money-bill-wave"></i> Employee Due
                    </a>
                </div>
            </div>
            <div class="sidebar-dropdown dropdown">
                <div class="sidebar-link dropdown-toggle1" onclick="this.parentElement.classList.toggle('open')">
                    <span><i class="fa-solid fa-credit-card"></i> Payments</span>
                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                </div>
                <div class="dropdown-menu">
                    <a class="dropdown-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
                        <i class="fa-solid fa-money-check"></i> Payments / Transactions
                    </a>
                    <a class="dropdown-item {{ request()->routeIs('admin.payment_methods.*') ? 'active' : '' }}" href="{{ route('admin.payment_methods.index') }}">
                        <i class="fa-solid fa-money-bill"></i> Payment Methods
                    </a>
                    <a class="dropdown-item {{ request()->routeIs('admin.accounts.*') ? 'active' : '' }}" href="{{ route('admin.accounts.index') }}">
                        <i class="fa-solid fa-university"></i> Bank & UPI Accounts
                    </a>
                </div>
            </div>
            <div class="sidebar-dropdown dropdown">
                <div class="sidebar-link dropdown-toggle1" onclick="this.parentElement.classList.toggle('open')">
                    <span><i class="fa-solid fa-chart-pie"></i> Income & Expenses</span>
                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                </div>
                <div class="dropdown-menu">
                    <a class="dropdown-item {{ request()->routeIs('admin.income.*') ? 'active' : '' }}" href="{{ route('admin.income.index') }}">
                        <i class="fa-solid fa-arrow-up text-emerald-600"></i> Income
                    </a>
                    <a class="dropdown-item {{ request()->routeIs('admin.expense.*') ? 'active' : '' }}" href="{{ route('admin.expense.index') }}">
                        <i class="fa-solid fa-arrow-down text-rose-600"></i> Expenses
                    </a>
                </div>
            </div>
            <div class="sidebar-dropdown dropdown">
                <div class="sidebar-link dropdown-toggle1" onclick="this.parentElement.classList.toggle('open')">
                    <span><i class="fa-solid fa-right-left"></i> Internal Transfer</span>
                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                </div>
                <div class="dropdown-menu">
                    <a class="dropdown-item {{ request()->routeIs('admin.internal-transfer.list') ? 'active' : '' }}" href="{{ route('admin.internal-transfer.list') }}">
                        <i class="fa-solid fa-list"></i> List
                    </a>
                </div>
            </div>
            @yield('sidebar-links')
        </nav>
    </div>
    
    <!-- Sidebar Footer -->
    <div class="sidebar-footer p-6 border-t border-slate-600">
        <div class="text-center text-slate-400 text-xs">
            <div class="mb-2">
                <i class="fa-solid fa-shield-halved"></i> Designed & Created By
            </div>
            <div><a href="https://www.linkedin.com/in/ravi-katudia-682a272b4/" target="_blank">Ravi Katudia</a></div>
        </div>
    </div>
</aside>

<script>
// Close dropdown when clicking outside
window.addEventListener('click', function(e) {
    document.querySelectorAll('.sidebar-dropdown').forEach(function(drop) {
        if (!drop.contains(e.target)) {
            drop.classList.remove('open');
        }
    });
});

// Add active class to current page
document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname;
    const sidebarLinks = document.querySelectorAll('.sidebar-link, .dropdown-item');
    
    sidebarLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
            // If it's a dropdown item, also open the parent dropdown
            const parentDropdown = link.closest('.sidebar-dropdown');
            if (parentDropdown) {
                parentDropdown.classList.add('open');
            }
        }
    });
});
</script> 