@include('layouts.partials.head')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">

<body class="font-sans text-gray-900 antialiased auth-bg">
    <div class="w-full max-w-md space-y-8 p-10 rounded-2xl auth-card">
        <div class="text-center">
            <div class="inline-block p-4 mb-4 rounded-full auth-logo">
                <i class="fa-solid fa-key fa-2x"></i>
            </div>
            <h1 class="text-3xl font-bold auth-title">Reset Password</h1>
            <p class="mt-2 auth-subtitle">Enter your new password below.</p>
        </div>
        <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
            @csrf
            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <!-- Email Address -->
            <div>
                <label for="email" class="text-sm font-medium form-label">Email</label>
                <div class="mt-1">
                    <input id="email" class="form-input" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" placeholder="you@example.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
            </div>
            <!-- Password -->
            <div>
                <label for="password" class="text-sm font-medium form-label">Password</label>
                <div class="mt-1">
                    <input id="password" class="form-input" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
            </div>
            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="text-sm font-medium form-label">Confirm Password</label>
                <div class="mt-1">
                    <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>
            <div>
                <button type="submit" class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white border border-transparent rounded-md shadow-sm auth-button">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</body>

