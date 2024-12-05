<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
</head>
<body>
    <h1>Edit Task</h1>

    <form action="/tasks/update/{{ $task->id }}" method="POST">
        @csrf
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="{{ $task->title }}"><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description">{{ $task->description }}</textarea><br>

        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
        </select><br>

        <!-- Using inline JavaScript (not recommended) -->
        <button type="submit" onclick="return confirm('Are you sure you want to save changes?')">Save</button>
    </form>

    <a href="/tasks">Back to Task List</a>
</body>
</html>
