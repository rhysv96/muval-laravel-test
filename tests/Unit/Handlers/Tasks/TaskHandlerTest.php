<?php

namespace Tests\Unit\Handlers\Tasks;

use App\Handlers\Tasks\DTOs\CreateTaskDTO;
use App\Handlers\Tasks\DTOs\UpdateTaskDTO;
use App\Handlers\Tasks\TaskHandler;
use App\Models\Task;

test('Creates a task', function () {
    /** @var TaskHandler $taskHandler */
    $taskHandler = app(TaskHandler::class);

    $title = fake()->word();
    $description = fake()->paragraph();
    $status = collect(Task::$statuses)->random();

    $task = $taskHandler->createTask(
        CreateTaskDTO::fromArray([
            'title' => $title,
            'description' => $description,
            'status' => $status,
        ])
    );

    expect($task)->toBeInstanceOf(Task::class)
        ->and($task->id)->toBeString()
        ->and($task->title)->toEqual($title)
        ->and($task->description)->toEqual($description)
        ->and($task->status)->toEqual($status);

    $this->assertDatabaseHas('tasks', [
        'title' => $title,
        'description' => $description,
        'status' => $status,
    ]);
});

test('Updates a task', function () {
    /** @var TaskHandler $taskHandler */
    $taskHandler = app(TaskHandler::class);

    /** @var Task $task */
    $task = Task::factory()->create();

    $title = fake()->word();
    $description = fake()->paragraph();
    $status = collect(Task::$statuses)->random();

    $updatedTask = $taskHandler->updateTask(
        UpdateTaskDTO::fromArray($task, [
            'title' => $title,
            'description' => $description,
            'status' => $status,
        ])
    );

    expect($updatedTask)->toBeInstanceOf(Task::class)
        ->and($updatedTask->id)->toEqual($task->id)
        ->and($updatedTask->title)->toEqual($title)
        ->and($updatedTask->description)->toEqual($description)
        ->and($updatedTask->status)->toEqual($status);

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'title' => $title,
        'description' => $description,
        'status' => $status,
    ]);
});
