<?php

namespace App\Handlers\Tasks\DTOs;

use App\Models\Task;

class UpdateTaskDTO
{
    public function __construct(
        public Task $task,
        public ?string $title,
        public ?string $description,
        public ?string $status,
    ) {}

    public static function fromArray(Task $task, array $data): static
    {
        return new static(
            $task,
            $data['title'],
            $data['description'],
            $data['status']
        );
    }
}
