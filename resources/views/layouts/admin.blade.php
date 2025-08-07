@include('layouts.partials.head')

<body class="admin-body min-h-screen" x-data="{ sidebarOpen: true }">
    @include('layouts.partials.header')
    <div class="flex min-h-screen h-screen">
        <div class="left-sidebar-container h-screen overflow-y-auto">
            @include('layouts.partials.sidebar')
        </div>
        <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
        <main class="main-content" style="overflow-y:auto; max-height:100vh;">
            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="alert alert-success mt-16" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger mt-16" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger mt-16" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
    @include('layouts.partials.footer')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            HSStaticMethods.autoInit();
            
            // Mobile menu functionality
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const sidebarContainer = document.querySelector('.left-sidebar-container');
            const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
            
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', function() {
                    sidebarContainer.classList.toggle('show');
                    mobileMenuOverlay.classList.toggle('show');
                    document.body.classList.toggle('menu-open');
                });
            }
            
            if (mobileMenuOverlay) {
                mobileMenuOverlay.addEventListener('click', function(e) {
                    if (e.target === mobileMenuOverlay) {
                        sidebarContainer.classList.remove('show');
                        mobileMenuOverlay.classList.remove('show');
                        document.body.classList.remove('menu-open');
                    }
                });
            }
            
            // Close mobile menu on window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 1200) {
                    sidebarContainer.classList.remove('show');
                    mobileMenuOverlay.classList.remove('show');
                    document.body.classList.remove('menu-open');
                }
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function () {
                let alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = 0;
                    setTimeout(() => alert.remove(), 750);
                });
            }, 5000);
        });
    </script>
</body>

</html>
