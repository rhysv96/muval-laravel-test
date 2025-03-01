@extends('layouts.base')

@section('title', 'Create a New Task')

@section('content')
    <form action="/tasks/store" method="POST">
        @csrf
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="{{ old('title') }}" required maxlength="255"><br>
        @error('title')
            <span class="error">
                {{ $message }}
            </span>
        @enderror
        <br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" value="{{ old('description') }}" required maxlength="255"></textarea><br>
        @error('description')
            <span class="error">
                {{ $message }}
            </span>
        @enderror
        <br>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            @foreach(App\Models\Task::$statuses as $status)
                <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>
                    {{ Str::title(str_replace('_', ' ', $status)) }}
                </option>
            @endforeach
        </select><br>
        @error('status')
            <span class="error">
                {{ $message }}
            </span>
        @enderror
        <br>

        <button type="submit">Create Task</button>
    </form>

    <a href="/tasks">Back to Task List</a>
@endsection
