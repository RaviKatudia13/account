@extends('layouts.admin')

@section('content')
<div class="w-full max-w-3xl mx-auto my-12 p-8 rounded-2xl auth-card bg-white shadow-lg space-y-8">
    <div class="text-center mb-6">
        <div class="inline-block p-4 mb-4 rounded-full auth-logo bg-indigo-50">
            <i class="fa-solid fa-user fa-2x text-indigo-600"></i>
        </div>
        <h1 class="text-3xl font-bold auth-title">Edit Profile</h1>
        <p class="mt-2 auth-subtitle">Manage your account information, password, and security.</p>
    </div>
    <div class="space-y-8">
        <div>
            @include('profile.partials.update-profile-information-form')
        </div>
        <div>
            @include('profile.partials.update-password-form')
        </div>
        <div>
            @include('profile.partials.delete-user-form')
        </div>
        <div class="p-6 bg-gray-50 rounded shadow">
            <h3 class="text-lg font-semibold mb-4">Two-Factor Authentication</h3>
            @if (auth()->user()->two_factor_secret)
                <p class="mb-2 text-green-600">Two-factor authentication is <strong>enabled</strong> on your account.</p>
                <form method="POST" action="{{ url('user/two-factor-authentication') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Disable 2FA</button>
                </form>
                <div class="mt-4">
                    <h4 class="font-semibold">Recovery Codes</h4>
                    <ul class="list-disc ml-6">
                        @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true) as $code)
                            <li>{{ $code }}</li>
                        @endforeach
                    </ul>
                </div>
            @else
                <p class="mb-2 text-yellow-600">Two-factor authentication is <strong>not enabled</strong> on your account.</p>
                <form method="POST" action="{{ url('user/two-factor-authentication') }}">
                    @csrf
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Enable 2FA</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
