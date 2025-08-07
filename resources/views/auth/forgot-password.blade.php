@include('layouts.partials.head')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">

<body class="font-sans text-gray-900 antialiased auth-bg">
    <div class="w-full max-w-md space-y-8 p-10 rounded-2xl auth-card">
        <div class="text-center">
            <div class="inline-block p-4 mb-4 rounded-full auth-logo">
                <i class="fa-solid fa-envelope fa-2x"></i>
            </div>
            <h1 class="text-3xl font-bold auth-title">Forgot Password</h1>
            <p class="mt-2 auth-subtitle">No problem. Enter your email and we'll send you a reset link.</p>
        </div>
        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf
            <!-- Email Address -->
            <div>
                <label for="email" class="text-sm font-medium form-label">Email</label>
                <div class="mt-1">
                    <input id="email" class="form-input" type="email" name="email" :value="old('email')" required autofocus placeholder="you@example.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
            </div>
            <div>
                <button type="submit" class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white border border-transparent rounded-md shadow-sm auth-button">
                    Email Password Reset Link
                </button>
            </div>
        </form>
    </div>
</body>
