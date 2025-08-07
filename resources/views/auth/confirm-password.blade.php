@extends('layouts.admin')

@section('content')
<div class="w-full max-w-md mx-auto my-16 p-8 rounded-2xl auth-card bg-white shadow-lg">
    <div class="text-center mb-6">
        <div class="inline-block p-4 mb-4 rounded-full auth-logo bg-indigo-50">
            <i class="fa-solid fa-lock fa-2x text-white-600"></i>
        </div>
        <h1 class="text-2xl font-bold auth-title mb-2">Confirm Password</h1>
        <p class="text-gray-500 auth-subtitle mb-4">This is a secure area of the application. Please confirm your password before continuing.</p>
    </div>
    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input id="password" name="password" type="password" required autocomplete="current-password"
                   class="block w-full px-3 py-2 border rounded-md shadow-sm form-input focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                   placeholder="••••••••">
            @error('password')
                <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
            @enderror
        </div>
        <div class="flex justify-end mt-4">
            <button type="submit"
                    class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white border border-transparent rounded-md shadow-sm auth-button bg-indigo-600 hover:bg-indigo-700 transition">
                Confirm
            </button>
        </div>
    </form>
</div>
@endsection
