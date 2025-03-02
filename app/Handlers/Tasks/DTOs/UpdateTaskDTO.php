<?php

namespace App\Handlers\Tasks\DTOs;

use App\Models\Task;
use App\Util\Undefined;

class UpdateTaskDTO
{
    public function __construct(
        public Task|Undefined $task,
        public string|Undefined $title,
        public string|Undefined $description,
        public string|Undefined $status,
        public string|Undefined|null $userId,
    ) {}

    public static function fromArray(Task $task, array $data): static
    {
        return new static(
            $task,
            $data['title'] ?? new Undefined,
            $data['description'] ?? new Undefined,
            $data['status'] ?? new Undefined,
            match (true) {
                is_null($data['user_id']) => null,
                ! isset($data['user_id']) => new Undefined,
                default => $data['user_id'],
            }
        );
    }
}
