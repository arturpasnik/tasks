<?php

namespace App\Services;

use App\DTO\TaskDto;
use App\Models\Task;

class TaskService
{
    public function store(TaskDto $taskDto) : Task
    {
        return Task::create([
            'user_id' => auth()->id(),
            'txt' => $taskDto->txt,
            'status' => $taskDto->status
        ]);
    }

    public function update(Task $task, $fields) : Task
    {
        return (tap($task)->update($fields));
    }

    public function delete(Task $task) : bool
    {
        return $task->delete();
    }
}
