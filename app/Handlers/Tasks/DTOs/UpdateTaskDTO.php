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
        public string|Undefined|null $user_id,
    ) {}

    public static function fromArray(Task $task, array $data): static
    {
        return new static(
            $task,
            $data['title'] ?? new Undefined,
            $data['description'] ?? new Undefined,
            $data['status'] ?? new Undefined,
            isset($data['user_id'])
                ? (is_null($data['user_id']) ? null : $data['user_id'])
                : new Undefined,
        );
    }
}
