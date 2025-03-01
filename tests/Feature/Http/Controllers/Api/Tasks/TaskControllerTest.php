<?php

namespace Tests\Unit\Handlers\Tasks;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\Uid\Ulid;

test('List tasks requires authentication', function () {
    $response = $this->withHeaders([
        'Accept' => 'application/json',
    ])->get('/api/tasks');

    $response->assertUnauthorized();
});

test('Create task requires authentication', function () {
    $response = $this->withHeaders([
        'Accept' => 'application/json',
    ])->post('/api/tasks');

    $response->assertUnauthorized();
});

test('Update task requires authentication', function () {
    /** @var Task $task */
    $task = Task::factory()->create();

    $response = $this->withHeaders([
        'Accept' => 'application/json',
    ])->patch("/api/tasks/$task->id");

    $response->assertUnauthorized();
});

test('Create task requires validation', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this->actingAs($user)->withHeaders([
        'Accept' => 'application/json',
    ])->post('/api/tasks', [
        'title' => '',
        'description' => fake()->paragraph(100),
        'status' => 'invalid',
    ]);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['title', 'description', 'status']);
});

test('Update task requires validation', function () {
    /** @var Task $task */
    $task = Task::factory()->create();

    /** @var User $user */
    $user = User::factory()->create();

    $response = $this->actingAs($user)->withHeaders([
        'Accept' => 'application/json',
    ])->patch("/api/tasks/$task->id", [
        'title' => '',
        'description' => fake()->paragraph(100),
        'status' => 'invalid',
    ]);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['title', 'description', 'status']);
});

test('Update task requires valid task ID', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $response = $this->actingAs($user)->withHeaders([
        'Accept' => 'application/json',
    ])->patch('/api/tasks/' . Ulid::generate(), [
        'title' => fake()->word(),
        'description' => fake()->paragraph(),
        'status' => collect(Task::$statuses)->random(),
    ]);

    $response->assertNotFound();
});

test('List tasks', function () {
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Collection $tasks */
    $tasks = Task::factory()->count(5)->create();

    $response = $this->actingAs($user)->withHeaders([
        'Accept' => 'application/json',
    ])->get('/api/tasks');

    $response->assertOk();
    $response->assertJson([
        'data' => [
            ...$tasks->sortByDesc('created_at')->map(fn ($task) => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status,
            ]),
        ],
        'total' => $tasks->count(),
    ]);
});

test('Creates a task', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $title = fake()->word();
    $description = fake()->paragraph();
    $status = collect(Task::$statuses)->random();

    $response = $this->actingAs($user)->withHeaders([
        'Accept' => 'application/json',
    ])->post('/api/tasks', [
        'title' => $title,
        'description' => $description,
        'status' => $status,
    ]);

    $response->assertCreated();
    $response->assertJson([
        'title' => $title,
        'description' => $description,
        'status' => $status,
    ]);
    $response->assertJsonPath('id', fn ($value) => is_string($value));

    $this->assertDatabaseHas('tasks', [
        'title' => $title,
        'description' => $description,
        'status' => $status,
    ]);
});

test('Updates a task', function () {
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Task $task */
    $task = Task::factory()->create();

    $title = fake()->word();
    $description = fake()->paragraph();
    $status = collect(Task::$statuses)->random();

    $response = $this->actingAs($user)->withHeaders([
        'Accept' => 'application/json',
    ])->patch("/api/tasks/$task->id", [
        'title' => $title,
        'description' => $description,
        'status' => $status,
    ]);

    $response->assertOk();
    $response->assertJson([
        'title' => $title,
        'description' => $description,
        'status' => $status,
    ]);
    $response->assertJsonPath('id', fn ($value) => is_string($value));

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'title' => $title,
        'description' => $description,
        'status' => $status,
    ]);
});
