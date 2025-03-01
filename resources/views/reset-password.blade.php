@extends('layouts.base')

@section('title', 'Reset password')

@section('content')
    <form action="{{ route('reset-password') }}" method="POST">
        @csrf
        <input type="hidden" id="token" name="token" value="{{ $token }}"><br><br>

        <label for="password">Email:</label><br>
        <input type="email" id="email" name="email" value="{{ old('email') ?? $email }}"><br><br>
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

        <button type="submit">Reset password</button>
    </form>

    <p>Already have an account? <a href="{{ route('login') }}">Login here</a>.</p>
@endsection
