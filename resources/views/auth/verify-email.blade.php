@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6 text-center">Verify Email Address</h1>
        <p class="text-gray-600 mb-6 text-center">Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.</p>

        @if (session('status') == 'verification-link-sent')
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                A new verification link has been sent to the email address you provided during registration.
            </div>
        @endif

        <form action="{{ route('verification.send') }}" method="POST" class="mb-4">
            @csrf
            <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600">Resend Verification Email</button>
        </form>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-gray-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-gray-600">Logout</button>
        </form>
    </div>
</div>
@endsection
