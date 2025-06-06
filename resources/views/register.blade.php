@extends('layouts.base')

@section('title', 'Register')

@section('content')
    <form action="{{ route('register') }}" method="POST">
        @csrf

        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required><br><br>
        @error('name')
            <span class="error">
                {{ $message }}
            </span>
        @enderror

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required><br><br>
        @error('email')
            <span class="error">
                {{ $message }}
            </span>
        @enderror

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        @error('password')
            <span class="error">
                {{ $message }}
            </span>
        @enderror

        <label for="password_confirmation">Confirm Password:</label><br>
        <input type="password" id="password_confirmation" name="password_confirmation" required><br><br>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="{{ route('login') }}">Login here</a>.</p>
@endsection
