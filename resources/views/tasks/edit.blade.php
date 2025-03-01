@extends('layouts.base')

@section('title', 'Edit Task')

@section('content')
    <form action="/tasks/update/{{ $task->id }}" method="POST">
        @csrf
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="{{ old('title') ?? $task->title }}">
        @error('title')
            <span class="error">
                {{ $message }}
            </span>
        @enderror
        <br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required maxlength="255">{{ old('description') ?? $task->description }}</textarea>
        @error('description')
            <span class="error">
                {{ $message }}
            </span>
        @enderror
        <br>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            @foreach(App\Models\Task::$statuses as $status)
                <option value="{{ $status }}" {{ (old('status') ?? $task->status) == $status ? 'selected' : '' }}>
                    {{ Str::title(str_replace('_', ' ', $status)) }}
                </option>
            @endforeach
        </select>
        @error('status')
            <span class="error">
                {{ $message }}
            </span>
        @enderror
        <br>

        <!-- Using inline JavaScript (not recommended) -->
        <button type="submit" onclick="return confirm('Are you sure you want to save changes?')">Save</button>
    </form>

    <a href="/tasks">Back to Task List</a>
@endsection
