<?php

namespace App\Handlers\Tasks;

use App\Handlers\Tasks\DTOs\CreateTaskDTO;
use App\Handlers\Tasks\DTOs\UpdateTaskDTO;
use App\Models\Task;

class TaskHandler
{
    /**
     * Create a task
     */
    public function createTask(CreateTaskDTO $dto): Task
    {
        return Task::create([
            'title' => $dto->title,
            'description' => $dto->description,
            'status' => $dto->status,
        ]);
    }

    /**
     * Update a task
     */
    public function updateTask(UpdateTaskDTO $dto): Task
    {
        $dto->task->update(array_filter([
            'title' => $dto->title,
            'description' => $dto->description,
            'status' => $dto->status,
        ]));

        return $dto->task->refresh();
    }
}
