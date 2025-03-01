@extends('layouts.base')

@section('title', 'Login')

@section('content')
    <form action="{{ route('login') }}" method="POST">
        @csrf

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required><br>
        @error('email')
            <span class="error">
                {{ $message }}
            </span>
        @enderror

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        @error('password')
            <span class="error">
                {{ $message }}
            </span>
        @enderror

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="{{ route('register') }}">Register here</a>.</p>
    <p>Forgot your password? <a href="{{ route('request-reset-password') }}">Reset here</a>.</p>
@endsection
