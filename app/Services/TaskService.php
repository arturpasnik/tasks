<?php

namespace App\Services;

use App\DTO\TaskDto;
use App\Enums\TaskStatus;
use App\Models\Task;

class TaskService
{
    public function store(TaskDto $taskDto)
    {
        return Task::create([
            'user_id' => auth()->id(),
            'txt' => $taskDto->txt,
            'status' => $taskDto->status
        ]);
    }
}
