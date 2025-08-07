@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-md py-8">
    <h2 class="text-2xl font-bold mb-6">Two-Factor Authentication</h2>
    <form method="POST" action="{{ url('/two-factor-challenge') }}">
        @csrf
        <div class="mb-4">
            <label for="code" class="block text-gray-700">Authentication Code</label>
            <input id="code" type="text" name="code" required autofocus class="w-full border rounded px-3 py-2 mt-1">
            @error('code')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="recovery_code" class="block text-gray-700">Recovery Code</label>
            <input id="recovery_code" type="text" name="recovery_code" class="w-full border rounded px-3 py-2 mt-1">
            @error('recovery_code')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Authenticate</button>
    </form>
</div>
@endsection 