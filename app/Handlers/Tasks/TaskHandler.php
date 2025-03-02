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
        $data = collect([
            'title' => $dto->title,
            'description' => $dto->description,
            'status' => $dto->status,
        ])->filter(fn ($value) => !($value instanceof Undefined))->toArray();

        $dto->task->fill($data);

        if (!($dto->user_id instanceof Undefined)) {
            if (!is_null($dto->user_id)) {
                $dto->task->user()->associate($dto->user_id);
            } else {
                $dto->task->user()->dissociate();
            }
        }

        $dto->task->save();

        return $dto->task->refresh();
    }
}
