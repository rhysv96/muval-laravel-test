@extends('layouts.base')

@section('title', 'Reset password')

@section('content')
    <form action="{{ route('request-reset-password') }}" method="POST">
        @csrf
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required><br><br>
        @error('email')
            <span class="error">
                {{ $message }}
            </span>
        @enderror

        <button type="submit">Request password reset</button>
    </form>

    <p>Already know your password? <a href="{{ route('login') }}">Login here</a>.</p>
    <p>Don't have an account? <a href="{{ route('register') }}">Register here</a>.</p>
@endsection
