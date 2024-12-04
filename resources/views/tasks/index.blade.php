<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tasks</title>
</head>
<body>
<h1>Task List</h1>
<ul>
    @foreach ($tasks as $task)
        <li>
            {{ $task->title }} - Assigned to: {{ $task->user->name ?? 'Unknown' }}
            <a href="/tasks/{{ $task->id }}/edit">Edit</a>
        </li>
    @endforeach
</ul>
</body>
</html>
