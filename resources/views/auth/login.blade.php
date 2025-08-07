@include('layouts.partials.head')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">

<body class="font-sans text-gray-900 antialiased auth-bg">
    <div class="w-full max-w-md space-y-10 p-10 rounded-2xl auth-card">
        <div class="text-center">
            <div class="inline-block p-4 mb-4 rounded-full auth-logo">
                <i class="fa-solid fa-lock fa-2x"></i>
            </div>
            <h1 class="text-3xl font-bold auth-title">Welcome Back</h1>
            <p class="mt-2 auth-subtitle">Login to access your dashboard.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />
        
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            <!-- Email Address -->
            <div>
                <label for="email" class="text-sm font-medium form-label">Email</label>
                <div class="mt-1">
                    <input id="email" name="email" type="email" :value="old('email')" required autofocus autocomplete="username"
                           class="block w-full px-3 py-2 border rounded-md shadow-sm form-input focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           placeholder="you@example.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="text-sm font-medium form-label">Password</label>
                <div class="mt-1">
                    <input id="password" name="password" type="password" required autocomplete="current-password"
                           class="block w-full px-3 py-2 border rounded-md shadow-sm form-input focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox"
                           class="w-4 h-4 border-gray-500 rounded form-checkbox focus:ring-indigo-500">
                    <label for="remember_me" class="block ml-2 text-sm auth-subtitle">Remember me</label>
                </div>

                @if (Route::has('password.request'))
                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" class="font-medium auth-link">
                            Forgot your password?
                        </a>
                    </div>
                @endif
            </div>

            <div>
                <button type="submit"
                        class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white border border-transparent rounded-md shadow-sm auth-button">
                    Log in
                </button>
            </div>
        </form>

        <p class="mt-8 text-sm text-center auth-subtitle">
            Not a member?
            <a href="{{ route('register') }}" class="font-medium auth-link">
                Sign up
            </a>
        </p>
    </div>
</body>
