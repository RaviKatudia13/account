@include('layouts.partials.head')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">

<body class="font-sans text-gray-900 antialiased auth-bg">
    <div class="w-full max-w-md space-y-8 p-10 rounded-2xl auth-card">
        <div class="text-center">
            <div class="inline-block p-4 mb-4 rounded-full auth-logo">
                <i class="fa-solid fa-user-plus fa-2x"></i>
            </div>
            <h1 class="text-3xl font-bold auth-title">Create an Account</h1>
            <p class="mt-2 auth-subtitle">Join us and start your journey.</p>
        </div>
        
        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf
            <!-- Name -->
            <div>
                <label for="name" class="text-sm font-medium form-label">Name</label>
                <div class="mt-1">
                    <input id="name" name="name" type="text" :value="old('name')" required autofocus autocomplete="name"
                           class="block w-full px-3 py-2 border rounded-md shadow-sm form-input focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           placeholder="Ravi Katudia">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
            </div>
            
            <!-- Email Address -->
            <div>
                <label for="email" class="text-sm font-medium form-label">Email</label>
                <div class="mt-1">
                    <input id="email" name="email" type="email" :value="old('email')" required autocomplete="username"
                           class="block w-full px-3 py-2 border rounded-md shadow-sm form-input focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           placeholder="ravikatudia@gmail.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="text-sm font-medium form-label">Password</label>
                <div class="mt-1">
                    <input id="password" name="password" type="password" required autocomplete="new-password"
                           class="block w-full px-3 py-2 border rounded-md shadow-sm form-input focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="text-sm font-medium form-label">Confirm Password</label>
                <div class="mt-1">
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                           class="block w-full px-3 py-2 border rounded-md shadow-sm form-input focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>

            <div>
                <button type="submit"
                        class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white border border-transparent rounded-md shadow-sm auth-button">
                    Register
                </button>
            </div>
        </form>

        <p class="mt-8 text-sm text-center auth-subtitle">
            Already a member?
            <a href="{{ route('login') }}" class="font-medium auth-link">
                Sign in
            </a>
        </p>
    </div>
</body>
</html>

