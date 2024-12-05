<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        foreach ($tasks as $task) {
            $task->user;
        }

        return view('tasks.index', ['tasks' => $tasks]);
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store()
    {
        $title = $_POST['title'];
        $description = $_POST['description'];

        DB::insert("INSERT INTO tasks (title, description) VALUES ('$title', '$description')");

        return redirect()->route('tasks.index');
    }

    public function edit($id)
    {
        $task = DB::select("SELECT * FROM tasks WHERE id = $id");

        if (!$task) {
            return "Task not found";
        }

        return view('tasks.edit', ['task' => $task[0]]);
    }

    public function update($id)
    {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $status = $_POST['status'];

        DB::update("UPDATE tasks SET title = '$title', description = '$description', status = '$status' WHERE id = $id");

        return redirect()->route('tasks.index');
    }

    public function destroy($id)
    {
        DB::delete("DELETE FROM tasks WHERE id = $id");

        return redirect()->route('tasks.index');
    }
}

