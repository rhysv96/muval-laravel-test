<?php

namespace App\Http\Controllers\Api\Tasks;

use App\Handlers\Tasks\DTOs\CreateTaskDTO;
use App\Handlers\Tasks\DTOs\UpdateTaskDTO;
use App\Handlers\Tasks\TaskHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Tasks\StoreTaskRequest;
use App\Http\Requests\Api\Tasks\TasksListRequest;
use App\Http\Requests\Api\Tasks\UpdateTaskRequest;
use App\Http\Resources\Tasks\TaskResource;
use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    /**
     * List tasks
     */
    public function index(TasksListRequest $request)
    {
        $tasks = Task::with(['user'])
            ->orderBy('created_at', 'DESC')
            ->paginate($request->per_page ?? 15);

        return response()->json($tasks->through(fn ($task) => new TaskResource($task)));
    }

    /**
     * Get task
     */
    public function get(Task $task)
    {
        return response()->json(new TaskResource($task));
    }

    /**
     * Create new task
     */
    public function store(StoreTaskRequest $request, TaskHandler $handler)
    {
        $task = $handler->createTask(CreateTaskDTO::fromArray($request->validated()));

        return response()->json(new TaskResource($task), Response::HTTP_CREATED);
    }

    /**
     * Update a task
     */
    public function update(UpdateTaskRequest $request, Task $task, TaskHandler $handler)
    {
        $task = $handler->updateTask(UpdateTaskDTO::fromArray($task, $request->validated()));

        return response()->json(new TaskResource($task));
    }

    /**
     * Delete a task
     */
    public function destroy(Task $task)
    {
        if (! $task->delete()) {
            Log::error('Failed to delete task', [
                'task_id' => $task->id
            ]);

            return response()->json([ 'success' => false ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([ 'success' => true ]);
    }
}
