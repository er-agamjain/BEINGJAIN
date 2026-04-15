@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6 text-center">Login</h1>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label for="remember" class="flex items-center">
                    <input type="checkbox" id="remember" name="remember" class="mr-2">
                    <span class="text-gray-700">Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600">Login</button>
        </form>

        <div class="mt-4 text-center">
            <p class="text-gray-600">Don't have an account? <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Register</a></p>
            <p class="text-gray-600 mt-2"><a href="{{ route('password.request') }}" class="text-blue-500 hover:underline">Forgot password?</a></p>
        </div>

        <div class="mt-4 text-center">
            <p class="text-gray-600">Or login with: <a href="{{ route('auth.google') }}" class="text-blue-500 hover:underline">Google</a></p>
        </div>
    </div>
</div>
@endsection
