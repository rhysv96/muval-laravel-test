<?php

namespace App\Handlers\Tasks;

use App\Handlers\Tasks\DTOs\CreateTaskDTO;
use App\Handlers\Tasks\DTOs\UpdateTaskDTO;
use App\Models\Task;
use App\Util\Undefined;

class TaskHandler
{
    /**
     * Create a task
     */
    public function createTask(CreateTaskDTO $dto): Task
    {
        $task = Task::make([
            'title' => $dto->title,
            'description' => $dto->description,
            'status' => $dto->status,
        ]);

        if (! is_null($dto->userId)) {
            $task->user()->associate($dto->userId);
        }

        $task->save();

        return $task;
    }

    /**
     * Update a task
     */
    public function updateTask(UpdateTaskDTO $dto): Task
    {
        $data = collect([
            'title' => $dto->title,
            'description' => $dto->description,
            'status' => $dto->status,
        ])->filter(fn ($value) => ! ($value instanceof Undefined))->toArray();

        $dto->task->fill($data);

        if (! ($dto->userId instanceof Undefined)) {
            if (! is_null($dto->userId)) {
                $dto->task->user()->associate($dto->userId);
            } else {
                $dto->task->user()->dissociate();
            }
        }

        $dto->task->save();

        return $dto->task->refresh();
    }
}
