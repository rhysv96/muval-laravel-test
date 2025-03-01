<?php

namespace App\Http\Controllers\Web\Tasks;

use App\Handlers\Tasks\DTOs\CreateTaskDTO;
use App\Handlers\Tasks\DTOs\UpdateTaskDTO;
use App\Handlers\Tasks\TaskHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Task\StoreTaskRequest;
use App\Http\Requests\Api\Task\UpdateTaskRequest;
use App\Models\Task;
use Exception;

class TaskController extends Controller
{
    /**
     * Show list tasks page
     */
    public function index()
    {
        // 1. use ->with over looping though and calling ->user.
        // This is far more performant, as laravel will call one `select * from users where id in (...)`,
        // rather than `select * from users where id = ? limit 1` for _every single task_
        // 2. Use paginate. Always use paginate! This is a performance bottleneck waiting to happen
        // 3. Check that the tasks belong to the authenticated user, this is a security flaw
        // 4. no default sort order. MySQL will sort arbitrarily in this case, no guarantee of sorting the same each time
        $tasks = Task::with(['user'])
            ->orderBy('created_at', 'DESC')
            ->paginate();

        // Updated view to support pagination
        // As an aside, I'm aware of `compact`, I just don't like it personally. Full arrays are a bit wordier but more explicit, in a good way.
        return view('tasks.index', ['tasks' => $tasks]);
    }

    /**
     * Show create tasks page
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Create new task
     */
    public function store(StoreTaskRequest $request, TaskHandler $handler)
    {
        // Never use $_POST, always use request.
        // Firstly, this is a security issue, laravel provides lots of protections and we're just bypassing it
        // Secondly, this is a security issue, we have no validation
        // Thirdly, this is a code cleanliness issue, request reads way better and has a lot of utility e.g. leveraging both query params and postback, json and form data

        // Absolutely not on the SQL injection. Eloquent should be used for a few reasons
        // Eloquent uses prepared statements behind the scenes, which are great. Very resiliant to sql injections,
        // as it informs MySQL of the shape of the statement being ran, and then informs it of the data. Complete separation, maximum safety
        // Another reason to use Eloquent is cleanliness, it reads way better
        // And finally, it's needed to trigger HasUlid
        // Also, put it behind a handler for testability
        $handler->createTask(CreateTaskDTO::fromArray($request->validated()));

        return redirect()->route('tasks.index');
    }

    /**
     * Show edit task page
     */
    public function edit(Task $task)
    {
        // Use route-model bindings over making another query within the controller method
        // This is tidier, also handles 404 more gracefully.
        // I've generated some error pages, but I haven't edited them at all. They're just in place for now

        return view('tasks.edit', ['task' => $task]);
    }

    /**
     * Update a task
     */
    public function update(UpdateTaskRequest $request, Task $task, TaskHandler $handler)
    {
        // Same issues as the store function, we should not be using $_POST. Use request!

        // Again, same issues as store, use Eloquent, don't expose yourself to SQL injection
        // I've also allowed partial updates, with ->only. Unnecessary, but nice to have
        // Also, put it behind a handler for testability
        $handler->updateTask(UpdateTaskDTO::fromArray($task, $request->validated()));

        return redirect()->route('tasks.index');
    }

    /**
     * Delete a task
     */
    public function destroy(Task $task)
    {
        // Again, same issue as store and update, we should use Eloquent here to avoid SQL injections
        // $task->delete would also leave us option to switching to soft-deletes, which is a nice bonus!
        if (! $task->delete()) {
            throw new Exception('Failed to delete task');
        }

        return redirect()->route('tasks.index');
    }
}
