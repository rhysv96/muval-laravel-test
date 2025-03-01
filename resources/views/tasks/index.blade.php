@extends('layouts.base')

@section('title', 'Task list')

@section('content')
@if (auth()->user()->hasVerifiedEmail())
    <a href="{{ route('tasks.create') }}">
        <button>Create New Task</button>
    </a>
@else
    <p>Please verify your email address to start modifying the board.</p>
    <form action="{{ route('verification.send') }}" method="POST">
        @csrf
        <button type="submit">Verify</button>
    </form>
@endif

<ul>
    @foreach ($tasks as $task)
        <li>
            {{ $task->title }} - Assigned to: {{ $task->user->name ?? 'Unknown' }}
            @if (auth()->user()->hasVerifiedEmail())
                <a href="/tasks/{{ $task->id }}/edit">Edit</a> | <a href="/tasks/{{ $task->id }}/delete">Delete</a>
            @endif
        </li>
    @endforeach

    {{ $tasks->links('pagination::default') }}
</ul>
<form action="{{ route('logout') }}" method="POST" style="display: inline;">
    @csrf
    <button type="submit">Logout</button>
</form>
@endsection
