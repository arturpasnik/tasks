<?php

namespace App\DTO;

use App\Enums\TaskStatus;

readonly class TaskDto
{
    public function __construct(
        public string $txt, public TaskStatus $status
    ){}

}
