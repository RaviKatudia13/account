<!-- Header Partial -->
<div class="app-topstrip">
    <div class="header-left">
        <!-- Mobile Menu Toggle Button -->
        <button type="button" class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <div class="header-logo-container">
            <a href="" class="header-logo">
                <span>
                    Welcome back! {{ ucfirst(substr(auth()->user()->name, 0 )) }}
                </span>
            </a>
        </div>
    </div>
    <div class="header-right">
        @auth
        <div class="header-dropdown dropdown">
            <div class="user-info" onclick="this.parentElement.classList.toggle('open')">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="user-details">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">Administrator</div>
                </div>
                <i class="fas fa-chevron-down dropdown-arrow"></i>
            </div>
            <div class="dropdown-menu">
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                    <i class="fas fa-user-edit"></i>
                    Edit Profile
                </a>
                <form method="POST" action="{{ route('logout') }}" class="dropdown-item-form">
                    @csrf
                    <button type="submit" class="dropdown-item logout-dropdown-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </div>
</div>

<script>
// Close header dropdown when clicking outside
window.addEventListener('click', function(e) {
    const headerDropdown = document.querySelector('.header-dropdown');
    if (headerDropdown && !headerDropdown.contains(e.target)) {
        headerDropdown.classList.remove('open');
    }
});
</script>