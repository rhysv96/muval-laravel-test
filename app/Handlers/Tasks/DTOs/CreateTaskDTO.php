<?php

namespace App\Handlers\Tasks\DTOs;

class CreateTaskDTO
{
    public function __construct(
        public string $title,
        public string $description,
        public string $status,
        public ?string $userId,
    ) {}

    public static function fromArray(array $data): static
    {
        return new static(
            $data['title'],
            $data['description'],
            $data['status'],
            $data['user_id'] ?? null,
        );
    }
}
